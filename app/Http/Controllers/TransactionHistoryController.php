<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\BuyBill;
use App\RechargeBill;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    public function index() {
    	$user = User::with(['buy_bills' => function($bill) {
                $bill->with(['user', 'account' => function($account) {
                    $account->with('user', 'game');
                }]);
            }, 'transfer_bills_sender' => function($q) {
                $q->with('from', 'to');
            }, 'transfer_bills_receiver' => function($q) {
                $q->with('from', 'to');
            }, 'recharge_bills', 'withdraw_bills'
        ])->find(Auth::id());

        if (Auth::user()->type == 1) {
            $bills = BuyBill::with('user', 'account')->whereHas('account', function($q) {
                $q->where('user_id', Auth::id());
            })->get();
            
            return view('transaction_history', compact('user', 'bills'));
        }
    	return view('transaction_history', compact('user'));
    }

    public function checkCard(Request $rq, RechargeBill $recharge_bill) {
        if ($recharge_bill->user->id === Auth::id() || Auth::user()->role === 1) {
            $telcoId = ['Viettel' => 1, 'Vinaphone' => 2, 'Mobiphone' => 3];
            if ($recharge_bill->type === 'card' && array_key_exists($recharge_bill->card->sim->name, $telcoId)) {
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

                    $url = "https://api.2ahvqkxsuzrrlvqigar8.com/paygate?command=fetchCardHistory&limit=2500&skip=0";
                    $curl = json_decode(Curl::to($url)->withHeader('authorization: ' . $accessToken)->get(), true);

                    $serial = $recharge_bill->card->serial;
                    $card = array_filter($curl['data']['items'], function($card) use ($serial) {
                        return $card['serial'] == $serial;
                    });
                    if (!empty($card)) {
                        $card = reset($card);

                        if ($card['status'] == 1099) {
                            // thẻ đang được xử lý
                            return redirect()->back()->withSuccess($card['statusMessage']);
                        } elseif ($card['status'] == 1010) {
                            // Giao dịch thất bại
                            $recharge_bill->confirm = -1;
                            $recharge_bill->reason = 'Thẻ không hợp lệ.';
                            $recharge_bill->save();

                            return redirect()->back()->withError($card['statusMessage']);
                        } elseif ($card['status'] == 0) {
                            // Giao dịch thành công
                            if ($recharge_bill->confirm === 0) {
                                $user = User::find(auth()->id());
                                $discount = preg_replace('/%/', '', $recharge_bill->card->sim->discount);
                                $cards = [
                                    10000 => [5000, 9000],
                                    20000 => [10000, 17200],
                                    30000 => [17201, 27000],
                                    50000 => [27001, 49999],
                                    100000 => [50000, 90000],
                                    200000 => [100000, 172000],
                                    300000 => [172001, 270000],
                                    500000 => [270001, 499990]
                                ];
                                foreach ($cards as $amount => $range) {
                                    if ($card['netValue'] >= $range[0] && $card['netValue'] <= $range[1]) {
                                        $cash = Auth::user()->cash + ($amount - ($amount * $discount / 100));
                                        break;
                                    }
                                }
                                
                                Auth::user()->cash = $cash;
                                Auth::user()->save();

                                $recharge_bill->confirm = 1;
                                $recharge_bill->save();
                            } else {
                                return redirect()->back()->withError('Thẻ này đã được kiểm tra thành công.');
                            }

                            return redirect()->back()->withSuccess($card['statusMessage']);
                        } else {
                            return redirect()->back()->withError("Error code: $card[status]. $card[statusMessage]");
                        }
                    } else {
                        $recharge_bill->delete();

                        return redirect()->back()->withError('Thẻ không nằm trên hệ thống.');
                    }
                } else {
                    return redirect()->back()->withError('Lỗi nạp thẻ, vui lòng liên hệ Admin.');
                }
            }
            return redirect()->back()->withError('Lỗi khi kiểm tra thẻ cào!');
        } else {
            return redirect()->back()->withError('Lỗi không thể kiểm tra hóa đơn!');
        }
    }
}
