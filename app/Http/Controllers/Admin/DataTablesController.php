<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Game;
use App\Package;
use App\Sim;
use App\RechargeBill;
use App\WithdrawBill;

class DataTablesController extends Controller
{
    public function listGame() {
    	$games = Game::select(['id', 'picture', 'name', 'sort_name', 'fee', 'info'])->get();
    	$rt_dt = [];
    	for ($i = 0; $i < count($games); $i++) {
    		$rt_dt[$i]['picture'] = '<img src="'.url($games[$i]['picture']).'" width="100" height="100">';
    		$rt_dt[$i]['name'] = $games[$i]['name'];
            $rt_dt[$i]['sort_name'] = $games[$i]['sort_name'];
            $rt_dt[$i]['fee'] = $games[$i]['fee'];
    		$rt_dt[$i]['info'] = $games[$i]['info'];
    		$rt_dt[$i]['actions'] = '<div class="btn-group btn-group-sm" role="group" aria-label="action button">
								<button type="button" class="btn btn-secondary" onclick="javascript:location.href = \''. route('admin.game.account.index', $games[$i]['id']) . '\'">Tài khoản</button>
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalGame" onclick="app.editGame('.$games[$i]['id'].')">Sửa</button>
								<button type="button" class="btn btn-danger" onclick="app.deleteGame('.$games[$i]['id'].')">Xóa</button>
							</div>';
    	}
    	return response($rt_dt, 200);
    }

    public function listAccount(Request $request) {
        $accounts = Game::find($request->game_id)->accounts;

        $rt_dt = [];
        for ($i = 0; $i < count($accounts); $i++) {
            $rt_dt[$i]['id'] = $accounts[$i]['id'];
            $rt_dt[$i]['username'] = $accounts[$i]['username'];
            $rt_dt[$i]['password'] = $accounts[$i]['password'];
            $rt_dt[$i]['price'] = number_format($accounts[$i]['price']) . ' đ';

            if ($accounts[$i]['client_status'] == 1) {
                $status = '<span>Tài khoản đã có người mua và đang chờ xác nhận.</span>';
                $action = '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#accountModal" onclick="app.actionAccount(\'confirm\', '.$accounts[$i]['id'].')">Xác nhận</button>
                        <button type="button" class="btn btn-warning" onclick="app.actionAccount(\'reject\', '.$accounts[$i]['id'].')">Sai TT</button>';
            } elseif ($accounts[$i]['client_status'] == 2) {
                $status = '<span>Tài khoản đã được người mua xác nhận!</span>';
                $action = '<button type="button" class="btn btn-danger" onclick="app.deleteAccount('.$accounts[$i]['id'].')">Xóa</button>';
            } elseif ($accounts[$i]['client_status'] == 0) {
                $status = '<span>Tài khoản đang được bán.</span>';
                $action = '<button type="button" class="btn btn-danger" onclick="app.deleteAccount('.$accounts[$i]['id'].')">Xóa</button>';
            } elseif ($accounts[$i]['client_status'] == -1) {
                if ($accounts[$i]['admin_status'] == 0) {
                    $status = '<span>Tài khoản bị báo cáo và đang chờ Admin xác nhận.</span>';
                    $action = '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#accountModal" onclick="app.actionAccount(\'confirm\', '.$accounts[$i]['id'].')">Xác nhận</button>
                            <button type="button" class="btn btn-warning" onclick="app.actionAccount(\'reject\', '.$accounts[$i]['id'].')">Sai TT</button>';
                } elseif ($accounts[$i]['admin_status'] == -1) {
                    $status = '<span>Admin đã xác nhận ACC này là sai TT.</span>';
                    $action = '<button type="button" class="btn btn-danger" onclick="app.deleteAccount('.$accounts[$i]['id'].')">Xóa</button>';
                } else {
                    $status = '<span>Admin đã xác nhận ACC này là đúng thông tin.</span>';
                    $action = '<button type="button" class="btn btn-danger" onclick="app.deleteAccount('.$accounts[$i]['id'].')">Xóa</button>';
                }
            }

            $rt_dt[$i]['status'] = $status;
            $rt_dt[$i]['actions'] = '<div class="btn-group btn-group-sm" role="group" aria-label="action button">
                                        <a class="btn btn-info" target="_blank" href="'.route('account.show', $accounts[$i]['id']).'">Xem TT</a>'.$action.'
                                    </div>';
        }
        return response($rt_dt, 200);
    }

    public function listSim() {
        $sim = Sim::all();
        $rt_dt = [];
        for ($i = 0; $i < count($sim); $i++) {
            $rt_dt[$i]['name'] = $sim[$i]['name'];
            $rt_dt[$i]['discount'] = $sim[$i]['discount'];
            $rt_dt[$i]['actions'] = '<div class="btn-group btn-group-sm" role="group" aria-label="action button">
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#simModal" onclick="app.editSim('.$sim[$i]['id'].')">Sửa</button>
                                    <button type="button" class="btn btn-danger" onclick="app.deleteSim('.$sim[$i]['id'].')">Xóa</button>
                                    </div>';
        }
        return response($rt_dt, 200);
    }

    public function listRechargeBill() {
        $recharge_bills = RechargeBill::with(['user', 'momo', 'nganluong', 'card' => function($q) {
            $q->with('sim');
        }])->get()->toArray();

        $rt_dt = [];
        for ($i = 0; $i < count($recharge_bills); $i++) {
            $rt_dt[$i]['created_at'] = $recharge_bills[$i]['created_at'];
            $rt_dt[$i]['id'] = $recharge_bills[$i]['id'];
            $rt_dt[$i]['customer_name'] = $recharge_bills[$i]['user']['name'];
            $rt_dt[$i]['money'] = number_format($recharge_bills[$i]['money']) . ' ₫';
            $rt_dt[$i]['payment_method'] = $recharge_bills[$i]['type'];
            if ($rt_dt[$i]['payment_method'] === 'card') {
                $rt_dt[$i]['card']['sim'] = (isset($recharge_bills[$i]['card']) && isset($recharge_bills[$i]['card']['sim'])) ? $recharge_bills[$i]['card']['sim']['name'] : null;
                $rt_dt[$i]['card']['serial'] = $recharge_bills[$i]['card']['serial'];
                $rt_dt[$i]['card']['code'] = $recharge_bills[$i]['card']['code'];
            } elseif ($rt_dt[$i]['payment_method'] === 'momo') {
                $rt_dt[$i]['momo']['phone'] = $recharge_bills[$i]['momo']['phone'];
                $rt_dt[$i]['momo']['code'] = $recharge_bills[$i]['momo']['code'];
            } elseif ($rt_dt[$i]['payment_method'] === 'nganluong') {
                $rt_dt[$i]['nganluong']['link'] = '<a href="'.route('recharge.order.check').'?order_id='.$recharge_bills[$i]['nganluong']['token'].'" target="_blank" class="btn btn-sm btn-primary">Kiểm tra hóa đơn</a>';
            }
            $rt_dt[$i]['actions'] = $recharge_bills[$i]['confirm'] === 0 ?
                '<div class="btn-group" role="group" aria-label="action button">
                    <button class="btn btn-sm btn-primary" onclick="app.action(\'confirm\', \''.$rt_dt[$i]['id'].'\')">Xác nhận</button>
                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalReason" onclick="app.order_id = \''.$rt_dt[$i]['id'].'\'">Hủy đơn</button>
                    <button class="btn btn-sm btn-danger" onclick="app.action(\'delete\', \''.$rt_dt[$i]['id'].'\')">Xóa</button>
                </div>' : ($recharge_bills[$i]['confirm'] === 0 ? 'Đã xác nhận.' : 'Bỏ.' . ' Lý do: ' . $recharge_bills[$i]['reason']);
        }
        return response($rt_dt);
    }

    public function listWithdrawBill() {
        $bills = WithdrawBill::with(['user' => function($q) {
            $q->with('bank');
        }])->get();

        $rt_dt = [];
        for ($i = 0; $i < count($bills); $i++) {
            $rt_dt[$i]['created_at'] = $bills[$i]['created_at'];
            $rt_dt[$i]['id'] = $bills[$i]['id'];
            $rt_dt[$i]['phone'] = $bills[$i]['phone'];
            $rt_dt[$i]['bank_name'] = $bills[$i]['user']['bank']['name'];
            $rt_dt[$i]['stk'] = $bills[$i]['user']['bank']['stk'];
            $rt_dt[$i]['master_name'] = $bills[$i]['user']['bank']['master_name'];
            $rt_dt[$i]['payment_method'] = $bills[$i]['type'];
            $rt_dt[$i]['money'] = number_format($bills[$i]['money']) . ' đ';

            if ($bills[$i]['confirm'] === 0) {
                $actions = '<div class="btn-group btn-group-sm" role="group" aria-label="action button">
                                <button type="button" class="btn btn-success" onclick="app.action(\'confirm\', \''.$bills[$i]['id'].'\')">Chấp nhận</button>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#withdrawModal" onclick="app.order_id=\''.$bills[$i]['id'].'\'">Hủy đơn</button>
                                <button type="button" class="btn btn-danger" onclick="app.deleteSim('.$bills[$i]['id'].')">Xóa</button>
                            </div>';

            } elseif ($bills[$i]['confirm'] === 1) {
                $actions = '<span>Hóa đơn đã được xác nhận!</span>';
            } elseif ($bills[$i]['confirm'] === -1) {
                $actions = '<span>Hóa đơn đã bị hủy!</span>';
            }
            $rt_dt[$i]['actions'] = $actions;
        }
        return response($rt_dt, 200);
    }
}
