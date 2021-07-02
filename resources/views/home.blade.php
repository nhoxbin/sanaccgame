@extends('layouts.app')

@section('content')
<div class="container">
    <div class="sl-hdtop">
        <div class="container">
            <div class="sl-boxs">
                <div class="sl-row clearfix">
                    <div class="col-md-12 hidden-xs">
                        <div class="sl-hdcbox">
                            <div class="sl-sebox">
                                <div class="sl-row clearfix">
                                    <div class="col-md-12">
                                        <h4 class="sl-htit sl-ht3">CHỌN GAME</h4>
                                        {{-- <div class="swiper-container slchgame">
                                            <ul class="swiper-wrapper">
                                                @foreach($games as $game)
                                                    <li class="swiper-slide">
                                                        <a href="{{ route('game.account.index', $game->id) }}">
                                                            <span class="center">
                                                                <img src="{{ asset($game->picture) }}" alt="{{ $game->name }}">
                                                            </span>
                                                            <h3>{{ $game->name }}</h3>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="swiper-scrollbar"></div>
                                        </div> --}}
                                        <div class="row">
                                            @foreach($games as $game)
                                                <div class="col-xs-12 col-sm-6 col-md-3 mt-5">
                                                    <a href="{{ route('game.account.index', $game->id) }}">
                                                        <span class="center">
                                                            <img src="{{ asset($game->picture) }}" alt="{{ $game->name }}">
                                                        </span>
                                                        <h3 class="text-center mt-2" style="font-size: 15px; color: #dadada;">{{ $game->name }}</h3>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sl-search">
        <div class="container">
            <div class="sl-sebox">
                <div class="sl-row clearfix row">
                    <div class="col-md-6">
                        <h4 class="sl-htit sl-ht3">LỌC ACC THEO GIÁ</h4>
                        <form role="form" method="get">
                            <ul class="sl-seauls clearfix">
                                <li>
                                    <select class="form-control property-filter" name="price">
                                        <option value="">Tìm theo giá</option>
                                        <option value="<50k">Dưới 50K</option>
                                        <option value="50k-200k">Từ 50K - 200K</option>
                                        <option value="200k-500k">Từ 200K - 500K</option>
                                        <option value="500k-1tr">Từ 500K - 1 Triệu</option>
                                        <option value=">1tr">Trên 1 Triệu</option>
                                    </select>
                                </li>
                                <!-- <li>
                                    <select class="form-control property-filter" name="rank">
                                        <option value="">Tìm theo rank</option>
                                    </select>
                                </li>
                                <li style="display: list-item;">
                                    <select class="form-control property-filter" data-filter="tim-theo-khung">
                                        <option value="">Tìm theo khung</option>
                                    </select>
                                </li>
                                <li>
                                    <select class="form-control property-filter" data-filter="sap-xep-theo">
                                        <option value="">Sắp xếp theo</option>
                                    </select>
                                </li> -->
                            </ul>
                            <ul class="sl-sebtns">
                                <li><input class="sl-sebt1 btn-filter" type="submit" value="Tìm"></li>
                                {{-- <li><button class="sl-sebt2" type="reset">LÀM LẠI</button></li> --}}
                            </ul>
                        </form>
                    </div>
                    @if(Auth::user())
                        <div class="col-md-6">
                            @if(Auth::user())
                                @if(Auth::user()->type === 1)
                                    <span>Cú pháp gỡ tài khoản đang bán bằng tin nhắn: <b style="color: red;">DV GOBAN ID</b> gửi <b style="color: red;">8785</b></span><br />
                                    <p>ID là ID của tài khoản muốn gỡ. Ví dụ: <b style="color: red;">ACC PUBG#1 (1 là ID)</b></p><br />
                                @endif
                            @endif

                            <span>Cú pháp nạp tiền bằng tin nhắn: <b style="color: red;">DV NAPSHOP {{ Auth::id() }}</b></span><br />
                            <span><b style="color: red;">Gửi 8785</b> +4.000đ</span><br />
                            <span><b style="color: red;">Gửi 8685</b> +2.500đ</span><br />
                            <span><b style="color: red;">Gửi 8585</b> +1.200đ</span><br />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- List account --}}
        <div class="container">
            <div class="sllpbox">
                <div id="list-account">
                    <div class="sl-produl clearfix">

                        @foreach($accounts as $account)
                            <div class="sl-prodli">
                                <div class="sl-prodbox">
                                    <a class="sl-prlinks" href="{{ route('account.show', $account->id) }}">
                                        <p class="sl-primg">
                                            <img src="{{ asset(json_decode($account->pictures, true)[0]) }}">
                                        </p>
                                        <div class="sl-prcode">
                                            @php
                                                $acc = $account->game->sort_name . '#' . $account->id;
                                                switch ($account->client_status) {
                                                    case -1:
                                                        if ($account->admin_status === -1) {
                                                            $acc .= ' (Sai TT, ngừng bán)';
                                                        } elseif ($account->admin_status === 0) {
                                                            $acc .= ' (Đã bán, đang chờ xác nhận chính xác)';
                                                        }
                                                        break;

                                                    case 0:
                                                        $acc .= ' (Đang bán)';
                                                        break;

                                                    case 1:
                                                        $acc .= ' (Đã bán, chờ người mua xác nhận)';
                                                        break;

                                                    case 2:
                                                        $acc .= ' (Đã bán)';
                                                        break;
                                                }
                                            @endphp
                                            <span>ACC {{ $acc }}</span>
                                        </div>
                                    </a>
                                    <div class="sl-prifs">
                                        <span class="sl-prpri sl-prpri1">{{ number_format($account->price) }} <sup>vnđ</sup></span>
                                        <br />
                                        <div class="sl-prifbot">
                                            <ul>
                                                @php
                                                    $account_info = array_combine(explode('|', $account->game->info), explode('|', $account->info));
                                                @endphp
                                                @foreach($account_info as $key => $info)
                                                    <li>{{ $key . ': ' . $info}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <p class="sl-prbot"><a style="cursor: pointer;" href="{{ route('account.show', $account->id) }}" class="sl-btnod">MUA NGAY</a></p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                {!! $accounts->links() !!}
            </div>

            <div id="loading" style="margin: 30px 0; text-align: center; display: none;">
                <img src="{{ asset('web/client/images/loading.gif') }}">
            </div>
        </div>
    </div>

    @include('layouts.footer')
</div>
@endsection
@push('css')
    <style>
        .swiper-container {
            width: 600px;
            height: 300px;
        }
    </style>
@endpush
@push('script')
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" />
    <script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>
    @if(Session::has('success') || Session::has('error'))
        <script>
            alert('{{ Session::get('success') ?? Session::get('error') }}');
        </script>
    @endif
    <script>
        $(document).ready(function() {
            var swiper = new Swiper('.swiper-container', {
                scrollbar: {
                    el: '.swiper-scrollbar',
                    draggable: true,
                },
                direction: 'horizontal',
                slidesPerView: 2,
                speed: 400,
                spaceBetween: 50
            })
        });
    </script>
@endpush
