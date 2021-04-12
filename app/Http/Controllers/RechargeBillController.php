<?php

namespace App\Http\Controllers;

use App\User;
use App\Card;
use App\Nganluong;
use App\Momo;
use App\Sim;
use App\RechargeBill;
use App\Http\Controllers\NLAPI;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RechargeBillController extends Controller
{
    public function smsRecharge(Request $rq) {
        $code = $rq->code; // Ma chinh (DV)
        $subCode = $rq->subCode; // Ma phu (NAPSHOP)
        $mobile = $rq->mobile; // So dien thoai +84
        $serviceNumber = $rq->serviceNumber; // Dau so 8x85 (8785: 4000, 8685: 2500, 8585: 1200)
        $info = $rq->info; // Noi dung tin nhan
        $arr = explode(' ', $info);

        if (count($arr) == 3 && $arr[0] == $code && $arr[1] == $subCode && is_numeric($arr[2])) {
            $user = User::find($arr[2]);
            if ($user === null) {
                $responseInfo = "User: ".$arr[2]." khong ton tai tren he thong.\n Vui long kiem tra lai.";
            } else {
                if ($serviceNumber == 8785) {
                    $user->cash += 4000;
                } elseif ($serviceNumber == 8685) {
                    $user->cash += 2500;
                } elseif ($serviceNumber == 8585) {
                    $user->cash += 1200;
                } else {
                    return '0|'."Gui sai so dich vu\nVui long gui dung so";
                }
                $user->save();

                $responseInfo = "Chuc mung ban da nap tien thanh cong! Chuc ban 1 ngay thuc su vui ve\nSo tien hien tai: ".$user->cash;
            }
        } else {
            $responseInfo = "Sai cu phap\nVui long nhap dung cu phap";
        }
        
        return '0|'.$responseInfo;
    }

    public function create() {
        $sims = Sim::all()->toArray();
        return view('recharge', compact('sims'));
    }

    public function store(Request $request) {
        try {
            if ($request->type === 'card') {
                $request->validate([
                    'sim_id' => 'required|numeric|exists:sims,id',
                    'money' => 'required|max:4|string',
                    'serial' => 'required|string|unique:cards',
                    'code' => 'required|string|unique:cards',
                ], [
                    '*.required' => 'Vui lòng điền đầy đủ!',
                    '*.unique' => 'Thẻ đã được sử dụng'
                ]);
                $amount = (int) preg_replace('/K/', '000', $request->money);
                $sim = Sim::find($request->sim_id);
                // $discount = preg_replace('/%/', '', $sim->discount);
                // $money = $amount - (($amount * $discount) / 100);

                $telcoId = ['Viettel' => 1, 'Vinaphone' => 2, 'Mobiphone' => 3];
                if (array_key_exists($sim->name, $telcoId)) {
                    $curl = json_decode(Curl::to('https://api.2ahvqkxsuzrrlvqigar8.com/id')
                        ->withData([
                            'command' => "loginHash",
                            'username' => 'hungvippy1112',
                            'password' => 'hungnohu',
                            'platformId' => 4,
                            'deviceId' => '446c87c2-a001-4b60-2b97-fb42ada14a3d',
                            'hash' => '0fa2c21e99efa7518c825626ab6b3f24',
                        ])->post(), true);

                    if ($curl['status'] == 0) {
                        $accessToken = $curl['data']['accessToken'];

                        $url = "https://api.2ahvqkxsuzrrlvqigar8.com/paygate?command=chargeCard&serial={$request->serial}&code={$request->code}&telcoId={$telcoId[$sim->name]}&amount={$amount}";
                        $curl = json_decode(Curl::to($url)->withHeader('authorization: ' . $accessToken)->get(), true);

                        if ($curl['status'] == 1099) {
                            // thẻ đang được xử lý
                            $msg = $curl['data']['message'] . '. Sau 5p, vui lòng vào lịch sử giao dịch để kiểm tra thẻ cào!';
                        } else {
                            // 1099: thẻ sử dụng sucess, 1: serial, code sai
                            return redirect()->back()->withError($curl['data']['message']);
                        }
                    } else {
                        // ko đăng nhập được
                        return redirect()->back()->withError($curl['data']['message']);
                    }
                }

                $recharge = RechargeBill::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => auth()->id(),
                    'money' => $amount,
                    'type' => 'card'
                ]);
                Card::create([
                    'recharge_bill_id' => $recharge->id,
                    'sim_id' => $sim->id,
                    'serial' => $request->serial,
                    'code' => $request->code
                ]);
                
                return redirect()->back()->withSuccess($msg ?? 'Số tiền sẽ được cộng vào tk khi thẻ hợp lệ');
            } elseif ($request->type === 'momo') {
                $request->validate([
                    'phone' => 'required|numeric|digits:10',
                    'code_momo' => 'required|numeric',
                    'money' => 'required|numeric|min:50000',
                ], [
                    '*.required' => 'Vui lòng điền đầy đủ!',
                    '*.numeric' => ':attribute phải là số',
                    'money.min' => 'Nạp từ :min trở lên'
                ]);
                $recharge = RechargeBill::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => auth()->id(),
                    'money' => $request->money,
                    'type' => 'momo'
                ]);
                Momo::create([
                    'recharge_bill_id' => $recharge->id,
                    'phone' => $request->phone,
                    'code' => $request->code_momo
                ]);
                return redirect()->back()->withSuccess('Hóa đơn đã được ghi nhận, số tiền tương ứng sẽ được cộng vào tài khoản khi hóa được xác nhận bởi Admin.');
            }
        } catch (\Exeption $e) {
            return redirect()->back()->withError('Có lỗi xảy ra, vui lòng thử lại hoặc liên hệ Admin');
        }
    }

    public function order(Request $rq) {
        if (!is_numeric($rq->total_amount) || $rq->total_amount < 50000) {
            return redirect()->back()->withError('Số tiền phải là số và nạp ít nhất 50000.');
        }
        $nlcheckout = new NLAPI();

        $total_amount = $rq->total_amount;
        $array_items = array();
        $payment_method = $rq->option_payment;
        $bank_code = @$rq->bankcode;
        $order_code = (string) Str::uuid();

        $payment_type = '';
        $discount_amount = 0;
        $order_description = '';
        $tax_amount = 0;
        $fee_shipping = 0;
        $return_url = route('recharge.order.check');
        $cancel_url = urlencode(route('recharge.order.cancel') .'?order_id='. $order_code);

        $buyer_fullname = $rq->buyer_fullname;
        $buyer_email = $rq->buyer_email;
        $buyer_mobile = $rq->buyer_mobile;

        $buyer_address = '';

        if ($payment_method != '' && $buyer_email != "" && $buyer_mobile != "" && $buyer_fullname != "" && filter_var($buyer_email, FILTER_VALIDATE_EMAIL)) {
            if ($payment_method == "ATM_ONLINE" && $bank_code != '') {
                $nl_result = $nlcheckout->BankCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
            } elseif ($payment_method == "IB_ONLINE" && $bank_code != '') {
                $nl_result = $nlcheckout->IBCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
            } elseif ($payment_method == "NH_OFFLINE" && $bank_code != '') {
                $nl_result = $nlcheckout->officeBankCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
            } elseif ($payment_method == "ATM_OFFLINE" && $bank_code != '') {
                $nl_result = $nlcheckout->BankOfflineCheckout($order_code, $total_amount, $bank_code, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items);
            } elseif ($payment_method == "VISA") {
                $nl_result = $nlcheckout->VisaCheckout($order_code, $total_amount, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items, $bank_code);
            } elseif ($payment_method == "CREDIT_CARD_PREPAID") {
                $nl_result = $nlcheckout->PrepaidVisaCheckout($order_code, $total_amount, $payment_type, $order_description, $tax_amount, $fee_shipping, $discount_amount, $return_url, $cancel_url, $buyer_fullname, $buyer_email, $buyer_mobile, $buyer_address, $array_items, $bank_code);
            } else {
                return redirect()->back()->withError('Vui lòng chọn đúng phương thức thanh toán!');
            }

            if (isset($nl_result->error_code) && $nl_result->error_code == '00') {
                // khi hóa đơn hợp lệ, lưu đơn hàng
                RechargeBill::create([
                    'id' => $order_code,
                    'user_id' => auth()->id(),
                    'money' => $total_amount,
                    'type' => 'nganluong'
                ]);

                // Cập nhât order với token $nl_result->token để sử dụng check hoàn thành sau này
                NganLuong::create([
                    'recharge_bill_id' => $order_code,
                    'token' => $nl_result->token
                ]);
                return redirect()->back()->with('checkout_url', (string) $nl_result->checkout_url);
            } else {
                return redirect()->back()->withError((string) $nl_result->error_message);
            }
        } else {
            return redirect()->back()->withError('Bạn chưa nhập đủ thông tin bên dưới.');
        }
    }

    private function orderStatus($token) {
        $nlcheckout = new NLAPI();
        $url = 'https://www.nganluong.vn/service/order/check';
        $params = array(
            'merchant_id' => $nlcheckout->merchant_id,
            'token' => $token,
            'checksum' => MD5($token . '|' . $nlcheckout->merchant_password),
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($result != '' && $status == 200) {
            $nl_result = json_decode($result);
            $nl_result->error_message = $nlcheckout->GetErrorMessage($nl_result->error_code);
        } else {
            $nl_result = $error;
        }

        return $nl_result;
    }

    public function checkOrder(Request $rq) {
        if (empty($rq->token)) {
            return redirect()->route('recharge.create');
        }
        $nl_result = $this->orderStatus($rq->token);

        $msg = '';
        if ($nl_result->data->transaction_status === '00' && $nl_result->data->payment_type == 1) {
            $nl = NganLuong::with(['recharge_bill' => function($q) {
                $q->with('user');
            }])->find($nl_result->data->order_code);
            if ($nl !== null) {
                $user = $nl->recharge_bill->user;
                if ($user->id === auth()->id() || $user->role === 1) {
                    if ($nl->recharge_bill->confirm === 1) {
                        $msg = 'Hóa đơn này đã được xác nhận!';
                    } else {
                        $nl->recharge_bill->confirm = 1;
                        $nl->recharge_bill->save();

                        $user->cash += $nl_result->data->total_amount;
                        $user->save();

                        return redirect()->route('recharge.create')->withSuccess('Thanh toán thành công!');
                    }
                } else {
                    $msg = 'Bạn không có quyền kiểm tra hóa đơn này!';
                }
            } else {
                $msg = 'Mã code không đúng!';
            }
        } else {
            if ($nl_result->data->payment_type == 2) {
                $msg = 'Đã cảnh báo không được phép nạp tạm giữ, số tiền sẽ không được cộng vào tài khoản!!!';
            } elseif ($nl_result->data->transaction_status !== '00') {
                $msg = $nl_result->error_message;
            } else {
                $msg = 'Không rõ lỗi, vui lòng liên hệ Admin';
            }
        }
        return redirect()->route('recharge.create')->withError($msg);
    }

    public function orderCancel(Request $rq) {
        if (empty($rq->order_id)) {
            return redirect()->route('recharge.create');
        }
        $order_id = $rq->order_id;
        $nl = NganLuong::find($order_id);
        if ($nl !== null) {
            $nl->recharge_bill->delete();
            return redirect()->route('recharge.create')->withSuccess('Hủy hóa đơn thành công.');
        }
        return redirect()->route('recharge.create')->withError('Không tìm thấy ID hóa đơn!');
    }
}
