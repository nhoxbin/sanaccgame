<?php

namespace App\Http\Controllers\Admin;

use App\WithdrawBill;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawBillController extends Controller
{
    public function index() {
        return view('admin.withdraw');
    }

    public function update(Request $request, WithdrawBill $withdraw) {
        $msg = '';
        if ($request->action === 'confirm') {
            $withdraw->confirm = 1;
            $withdraw->save();

            $msg = 'Hóa đơn đã được xác nhận!';
        } elseif ($request->action === 'reject') {
            $withdraw->confirm = -1;
            $withdraw->reason = $request->reason ?? null;
            $withdraw->save();

            // hoàn tiền
            $withdraw->user->cash += $withdraw->money;
            $withdraw->user->save();

            $msg = 'Đã hủy đơn.';
        }
        return response($msg);
    }

    public function destroy($id)
    {
        //
    }
}
