<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePassController extends Controller
{
	public function showChangePassForm() {
		return view('auth.change-pass');
	}

	public function changePassword(Request $rq) {
		$rq->validate([
			'old_password' => 'required|string|min:8',
			'password' => 'required|string|min:8|confirmed'
		]);

		if (Hash::check($rq->old_password, Auth::user()->password)) {
			$user = User::find(Auth::id());
			$user->password = bcrypt($rq->password);
			$user->save();
		}
		return redirect()->back()->withSuccess('Đổi mật khẩu thành công!');
	}

    public function smsResetPass(Request $rq) {
    	$code = $rq->code; // Ma chinh (ON)
		$subCode = $rq->subCode; // Ma phu (SANACCGAME)
		$mobile = $rq->mobile; // So dien thoai +84
		$serviceNumber = $rq->serviceNumber; // Dau so 8x85
		$info = $rq->info; // Noi dung tin nhan
		$arr = explode(' ', $info);

		if (count($arr) == 3 && $arr[0] == $code && $arr[1] == $subCode) {
			$user = User::where('phone', preg_replace('/84/', '0', $mobile))->first();
			if ($user === null) {
				$responseInfo = "SDT: ".$mobile." khong ton tai tren he thong.\n Vui long kiem tra lai.";
			} else {
				$user->password = bcrypt($arr[2]);
				$user->save();

				$responseInfo = "Chuc mung ban da doi mat khau thanh cong! Chuc ban 1 ngay thuc su vui ve\nMat khau hien tai: $arr[2]";
			}
		} else {
			$responseInfo = "Sai cu phap\nVui long nhap dung cu phap";
		}
		
		return '0|'.$responseInfo;
    }
}
