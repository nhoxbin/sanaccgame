<?php

namespace App\Http\Controllers;

use Auth;
use App\Account;
use App\Game;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /*public function __construct() {
        $this->middleware('auth');
    }*/

    public function index(Request $rq) {
        $games = Game::all();

        if (Auth::check()) {
            $accounts = Account::with('game');

            if (Auth::user()->type == 1) {
                // nếu đang đăng nhập acc bán thì chỉ hiện những tk họ bán
                $accounts = $accounts->where('user_id', auth()->id());
            } else {
                // acc mua. chỉ hiển thị những tk đang bán có code là 0
                $accounts = $accounts->where('client_status', 0);
            }
        } else {
            $accounts = Account::with('game')->where('client_status', 0);
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
        $accounts = $accounts->orderBy('created_at', 'desc')->paginate(20);

        $dashboard['number_user'] = User::count() + 1000;
        $dashboard['number_account_done'] = Account::where('client_status', 2)->count() + 300;
        $dashboard['number_account_selling'] = Account::where('client_status', 0)->count();

        return view('home', compact('games', 'accounts', 'dashboard'));
    }
}
