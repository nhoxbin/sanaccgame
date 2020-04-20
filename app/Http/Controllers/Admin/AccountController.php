<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\Game;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Game $game) {
        $game = $game->toArray();
        return view('admin.account', compact('game'));
    }

    public function update(Request $request, Game $game, Account $account) {
        if (!$request->ajax()) {
            return response(null, 400);
        }

        // nếu admin đã xác nhận tài khoản đồng nghĩa người mua cũng xác nhận
        $msg = '';
        if ($request->action === 'confirm') {
            $msg = 'Xác nhận tài khoản thành công!';
            $account->admin_status = 1; // đã xác nhận
            if ($account->client_status != 2) {
                $account->client_status = 2; // 2 = Đã xác nhận

                $fee = preg_replace('/\%/', '', $account->game->fee);
                $total = ($account->user->cash + $account->price) - (($account->price / 100) * $fee);
                $account->user->cash = $total;
                $account->user->save();
            }
        } elseif ($request->action === 'reject') {
            if ($account->client_status != 2) {
                $account->admin_status = -1; // sai TT account
                // khi admin đã xác nhận là sai TT, cộng tiền lại cho user đã mua
                $account->buy_bill->user->cash += $account->price;
                $account->buy_bill->user->save();

                $msg = 'Đã xác nhận là sai thông tin tài khoản!';
            } else {
                $account->admin_status = 1; // đã xác nhận

                $msg = 'user đã xác nhận tài khoản này';
            }
        }
        $account->save();
        
        return response($msg, 200);
    }

    public function destroy(Request $request, Game $game, Account $account) {
        if (!$request->ajax()) {
            return response(null, 400);
        }
        try {
            $account->delete();
            return response('ok', 200);
        } catch(\Exception $e) {
            return response($e->getMessage(), 200);
        }
    }
}
