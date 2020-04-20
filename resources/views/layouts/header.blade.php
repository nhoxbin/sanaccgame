<!-- Left Side Of Navbar -->
@auth
    <ul class="navbar-nav mr-auto">
        @if(Auth::user()->type === 1)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('account.create') }}">{{ __('Bán acc') }}</a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" href="{{ route('history.index') }}">{{ __('Lịch sử giao dịch') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('instruction') }}">{{ __('Hướng dẫn') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('recharge.create') }}">{{ __('Nạp tiền') }}</a>
        </li>
        @if(Auth::user()->type == 1)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('withdraw.create') }}">{{ __('Rút tiền') }}</a>
            </li>
        @endif
        @if(Auth::user()->is_transfer == 1)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('transfer.create') }}">{{ __('Chuyển tiền') }}</a>
            </li>
        @endif
    </ul>
@endauth

<!-- Right Side Of Navbar -->
<ul class="navbar-nav ml-auto">
    <!-- Authentication Links -->
    @guest
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">{{ __('Đăng nhập') }}</a>
        </li>
        @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Đăng ký') }}</a>
            </li>
        @endif
    @else
        <li class="nav-item">
            <span class="nav-link">{{ number_format(Auth::user()->cash) }} đ</span>
        </li>
        <li class="nav-item">
            <span class="nav-link">ID: {{ Auth::id() }}</span>
        </li>
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('password.change') }}">
                    {{ __('Đổi password') }}
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                    {{ __('Đăng xuất') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    @endguest
</ul>