<?php

namespace App\Http\Controllers;

use Auth;
use App\Account;
use App\Game;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
	public function index(Request $rq, Game $game) {
		$games = Game::all();
		$accounts = Account::with('game')->where('game_id', $game->id);
		if (Auth::user()->type == 1) {
            // nếu đang đăng nhập acc bán thì chỉ hiện những tk họ bán
            $accounts = $accounts->where('user_id', auth()->id());
        } else {
            // acc mua. chỉ hiển thị những tk đang bán có code là 0
            $accounts = $accounts->where('client_status', 0);
        }
        if (!empty($rq->price)) {
            switch ($rq->price) {
                case '<50k':
                    $accounts = $accounts->where('price', '<', 50000);
                    break;

                case '50k-200k':
                    $accounts = $accounts->whereBetween('price', [50000, 200000]);
                    break;

                case '200k-500k':
                    $accounts = $accounts->whereBetween('price', [200000, 500000]);
                    break;

                case '500k-1tr':
                    $accounts = $accounts->whereBetween('price', [500000, 1000000]);
                    break;

                case '>1tr':
                    $accounts = $accounts->where('price', '>', 1000000);
                    break;
            }
        }
        $accounts = $accounts->paginate(20);

		$dashboard['number_user'] = User::count() + 1000;
        $dashboard['number_account_done'] = Account::where('client_status', 2)->count() + 300;
        $dashboard['number_account_selling'] = Account::where('client_status', 0)->count();

		return view('home', compact('accounts', 'games', 'dashboard'));
	}

    public function create(Request $request) {
    	$games = Game::all();
    	if ($games->first() == null) {
    		return redirect()->route('home')->withError('Hiện tại chưa có game để bán tài khoản!');
    	}
    	return view('sell_account', compact('games'));
    }

    public function store(Request $request) {
		$request->validate([
			'username' => 'required|string',
			'password' => 'required|string',
			'contact_phone' => 'required|numeric|digits:10',
			'contact_link' => 'required|url',
			'price' => 'required|numeric|min:50000',
			'info' => 'required|array',
			'description' => 'string',
			'game_id' => 'required|numeric',
			'pictures' => 'required|array|between:2,50'
		], [
			'*.required' => 'Trường này không được bỏ trống',
			'pictures.array' => 'Hình phải là một mảng',
			'pictures.between' => 'Phải có ít nhất trên :min - :max tấm hình để mô tả tài khoản',
			'*.numeric' => ':attribute phải là số',
			'price.min' => 'Acc phải trị giá trên 50K'
		]);

		// validate pictures
		$pictures = $request->pictures;
		$imgRules = ['pictures' => 'required|image'];
		foreach ($pictures as $picture) {
			$validation = Validator::make(['pictures' => $picture], $imgRules, ['pictures.image' => 'Sai định dạng các file hình ảnh']);
			if ($validation->fails()) {
				$msg = $validation->messages();
				return redirect()->back()->withErrors($msg);
			}
		}

		// kiểm tra xem user đã đăng bán acc chưa
		$user = User::findOrFail(Auth::id());
    	if ($user->accounts->first()) {
	    	if ($user->cash < 15000) {
	    		return redirect()->route('home')->withError('Chúng tôi thu phí 15k khi bán acc lần 2 trên web. Vui lòng nạp thêm tiền!');
	    	} else {
	    		// thu phí khi bán acc lần 2
				$user->cash -= 15000;
		    	$user->save();
	    	}
    	}

		$len = count($request->info);
		$strInfo = '';
		for ($i = $len-1; $i >= 0; $i--) {
			$strInfo .= $request->info[$i];
			if ($i > 0) {
				$strInfo .= '|';
			}
		}

		try {
			$account = Account::create([
				'user_id' => auth()->id(),
				'game_id' => $request->game_id,
				'username' => $request->username,
				'password' => $request->password,
				'contact_phone' => $request->contact_phone,
				'contact_link' => $request->contact_link,
				'price' => $request->price,
				'info' => $strInfo,
				'description' => $request->description
			]);

			foreach ($pictures as $key => $picture) {
				$pic_name = $picture->getClientOriginalName();
				$picture->move('uploads/account/' . $account->id, $pic_name);
				$name = 'uploads/account/' . $account->id . '/' . $pic_name;
				$arrPicture[] = $name;
			}
			$account->pictures = json_encode($arrPicture);
			$account->save();

			return redirect()->route('home')->withSuccess('Tài khoản đã được đăng bán trên web');
		} catch (\Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}

	public function show(Account $account) {
		// chỉ hiển thị những ACC cho user đã mua và user bán ACC này xem
		if (!Auth::user()) {
			return view('account', compact('account'));
		}
		if ($account->client_status === 0 ||
			Auth::user()->role === 1 ||
			(!empty($account->buy_bill) &&
				$account->buy_bill->user_id === auth()->id()) ||
			$account->user_id === auth()->id()) {
			return view('account', compact('account'));
		}
		return redirect()->route('home')->withError('ACC này đã có người mua, vui lòng chọn ACC khác!');
	}

	public function update(Request $request, Account $account) {
		$msg = '';
		if ($request->action === 'confirm') {
			$fee = preg_replace('/\%/', '', $account->game->fee);
			// trừ theo phí của mỗi game
			$total = ($account->user->cash + $account->price) - (($account->price / 100) * $fee);
			$account->user->cash = $total;
			$account->user->save();

			$account->client_status = 2;
			$account->admin_status = 1;

			$msg = 'Xác nhận ACC thành công!';
		} elseif ($request->action === 'reject') {
			$account->client_status = -1;
			$account->buy_bill->reason = $request->reason ?? null;
			$account->buy_bill->save();

			$msg = 'Đã báo cáo ACC, đợi Admin xác nhận';
		}
		$account->save();

		return response($msg);
	}

	public function smsdelete(Request $rq) {
        $code = $rq->code; // Ma chinh (DV)
        $subCode = $rq->subCode; // Ma phu (GOBAN)
        $mobile = $rq->mobile; // So dien thoai +84
        $serviceNumber = $rq->serviceNumber; // Dau so 8785
        $info = $rq->info; // Noi dung tin nhan
        $arr = explode(' ', $info);

        if (count($arr) == 3 &&
            is_numeric($arr[2]) &&
            $arr[0] == $code &&
            $arr[1] == $subCode &&
            $serviceNumber == '8785'
        ) {
            $user = User::where('phone', preg_replace('/84/', '0', $mobile))->first();
            if ($user === null) {
                $responseInfo = "SDT: ".$mobile." khong ton tai tren he thong.\n Vui long kiem tra lai.";
            } else {
                $account = $user->accounts->find($arr[2]);
                if ($account === null) {
                    $responseInfo = "Tai khoan khong ton tai tren he thong, Xin vui long kiem tra lai.";
                } else {
                    $account->delete();
                    $responseInfo = "Tai khoan co ID la $arr[2] da bi xoa.\nCam on ban da su dung dich vu";
                }
            }
        } else {
            $responseInfo = "Sai cu phap\nVui long nhap dung cu phap";
        }
        
        return '0|'.$responseInfo;
    }
}
