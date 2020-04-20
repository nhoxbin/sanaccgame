<?php

namespace App\Http\Controllers\Admin;

use App\BuyBill;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuyBillController extends Controller
{
    public function index() {
        $buy_bills = BuyBill::with(['user', 'account' => function($q) {
            $q->with('game', 'user');
        }])->whereHas('account', function($q) {
            $q->where('client_status', 2);
        })->get()->toArray();

        return view('admin.buy', compact('buy_bills'));
    }

    public function update(Request $request, $id)
    {
        $buy_bills = BuyBill::with('user', 'package')->find($id);
        $msg = '';
        if ($request->action === 'confirm') {
            $buy_bills->confirm = 1;
            $buy_bills->comment = 'Đơn hàng được chấp nhận.';
            $msg = 'Xác nhận thành công!';
        } elseif ($request->action === 'reject') {
            $buy_bills->confirm = -1;
            $buy_bills->comment = 'Đơn hàng bị từ chối!';
            $buy_bills->reason = $request->reason;
            $buy_bills->user->cash += $buy_bills->package->money;
            $buy_bills->user->save();
            $msg = 'Đã hủy đơn hàng.';
        }
        $buy_bills->save();
        
        return response($msg, 200);
    }
}
