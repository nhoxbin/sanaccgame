<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\TransferBill;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TransferBillController extends Controller
{
    public function create() {
    	return view('transfer');
    }

    public function store(Request $request) {
        $user = Auth::user();
        $request->validate([
        	'money' => "required|numeric|min:100000|max:$user->cash",
        	'to' => "required|numeric|exists:users,id|not_in:$user->id"
        ], [
        	'money.min' => 'Chuyển từ 100k.',
        	'money.max' => 'Số tiền không đủ, vui lòng nạp thêm tiền.',
        	'to.not_in' => 'Bạn không được chuyển tiền cho chính bạn!',
        	'to.exists' => 'ID không tồn tại.'
        ]);

        try {
        	$money = $request->money;

        	$user = User::find($user->id);
        	$user->cash -= $money;
        	$user->save();

            $to = User::find($request->to);
        	$to->cash += $money;
        	$to->save();

        	TransferBill::create([
        		'id' => (string) Str::uuid(),
        		'user_id' => auth()->id(),
        		'to_user_id' => $to->id,
        		'money' => $money
        	]);
        	return redirect()->back()->withSuccess('Chuyển tiền thành công!');
        } catch (\Exception $e) {
        	return redirect()->back()->withError($e->getMessage());
        }
    }
}
