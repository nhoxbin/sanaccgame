<?php

namespace App\Http\Controllers;

use Auth;
use App\Bank;
use App\WithdrawBill;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WithdrawBillController extends Controller
{
    public function create() {
    	$bank = Bank::where('user_id', Auth::id())->first();
    	if ($bank === null) {
    		$bank = [
    			'name' => '',
    			'stk' => '',
    			'master_name' => ''
    		];
    	} else {
    		$bank = $bank->toArray();
    	}
    	return view('withdraw', compact('bank'));
    }

    public function store(Request $request) {
		$max = Auth::user()->cash;
    	if ($request->type === 'bank') {
			$request->validate([
				'bank_name' => 'required|string',
				'stk' => 'required|numeric|min:10',
				'master_name' => 'required|string',
				'money' => 'required|numeric|min:200000|max:'.($max-11000)
			], [
				'money.min' => 'Phải rút lớn hơn :min đ',
                'money.max' => 'Phải rút nhỏ hơn :max đ. Vì khi rút bằng ngân hàng, hệ thống sẽ trừ 11k phí chuyển',
                'stk.min' => 'STK phải trên 10 số.'
			]);
            try {
                $user = User::find(Auth::id());
                $user->cash -= 11000;
                $user->cash -= $request->money;
                $user->save();

                $bank = Bank::find(Auth::id());
                if ($bank === null) {
                    $bank = new Bank();
                    $bank->user_id = Auth::id();
                }
                $bank->name = $request->bank_name;
                $bank->stk = $request->stk;
                $bank->master_name = $request->master_name;
                $bank->save();
            } catch(\Exeption $e) {
                return redirect()->back()->withError('Có lỗi xảy ra!');
            }
    	} elseif ($request->type === 'momo') {
			$request->validate([
				'phone_number' => 'required|numeric|digits:10',
				'money' => "required|numeric|between:100000,$max"
			], [
				'money.between' => 'Rút trên :min và nhỏ hơn :max đ'
			]);
            try {
                $user = User::find(Auth::id());
                $user->cash -= $request->money;
                $user->save();
            } catch(\Exeption $e) {
                return redirect()->back()->withError('Có lỗi xảy ra!');
            }
    	}

    	WithdrawBill::create([
    		'id' => (string)Str::uuid(),
    		'user_id' => Auth::id(),
    		'money' => $request->money,
    		'phone' => $request->phone_number ?? null,
    		'type' => $request->type
    	]);

    	return redirect()->back()->withSuccess('Tạo lệnh rút tiền thành công!, chờ Admin chuyển tiền.');
    }
}
