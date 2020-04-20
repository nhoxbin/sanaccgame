<?php

namespace App\Http\Controllers;

use Auth;
use App\BuyBill;
use App\Game;
use App\User;
use App\Account;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BuyBillController extends Controller
{
    public function store(Request $request) {
        if (!$request->ajax()) {
            return response(null, 400);
        }

        $validator = Validator::make($request->all(), [
            'account_id' => 'required|numeric|exists:accounts,id',
            'reason' => 'nullable|string'
        ]);
        if ($validator->fails()) {
            return response(['success' => false, 'message' => 'Tài khoản không tồn tại trên hệ thống!'], 200);
        }

        // ktra là acc mua mới được phép mua và trừ tiền acc hiện tại
        if (Auth::user()->type == 1) {
            return response(['success' => false, 'message' => 'Tài khoản này chỉ được bán, không được mua!'], 200);
        }
        // lấy ra tài khoản user muốn mua
        $buying_account = $request->account_id;
        $account = Account::find($buying_account);
        if ($account->client_status != 0) {
            return response(['success' => false, 'message' => 'ACC này đã có người mua!'], 200);
        }
        if ($account->price > Auth::user()->cash) {
            return response(['success' => false, 'message' => 'Số tiền hiện có không đủ để mua tài khoản này! Vui lòng nạp thêm tiền!'], 200);
        }

        $user = User::find(auth()->id());
        // trừ tiền user khi đã mua
        $user->cash -= $account->price;
        $user->save();

        // chuyển trạng thái tài khoản sang đã bán (1)
        $account->client_status = 1;
        $account->save();

        // lưu vào hóa đơn bán
        BuyBill::create([
            'id' => (string) Str::uuid(),
            'user_id' => auth()->id(),
            'account_id' => $buying_account,
            'reason' => $request->reason
        ]);
        return response(['success' => true, 'message' => 'Mua tài khoản thành công!'], 200);
    }
}
