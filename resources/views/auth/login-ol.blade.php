@extends('layouts.auth')

@section('content')
	<div class="col-md-6 col-md-offset-1">
		<div class="content-main">
			<a href="./" class="logo"><img src="{{ asset('web/client/images/logo.png') }}" alt=""></a>
			<div class="tt">Đăng Nhập Thành Viên</div>
			<form method="POST" action="{{ route('login') }}">
				@csrf
				
				<div class="input-info @error('email') input-alert @enderror">
					<input type="email" class="input-pop" placeholder="Số điện thoại" id="email"  style="height: 50px; font-size: 16px" name="email" value="{{ old('email') }}" required>

					@error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
				</div>
				<div class="input-info @error('password') input-alert @enderror">
					<input type="password" class="input-pop" placeholder="Mật khẩu" id="password" style="height: 50px; font-size: 16px" name="password" value="{{ old('password') }}" required>

					@error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
				</div>

				<button type="submit" class="btn btn-info btn-block" id="btnLogin" style="font-size: 16px; padding: 17px 0px; margin-bottom: -15px; margin-top: 14px;">
					<span>Đăng nhập</span>
				</button>
			</form>

			<p id="error-input" style="color: red; text-align: center"></p>
			<div class="quen">
				@if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.reset') }}">
                        {{ __('Quên mật khẩu?') }}
                    </a>
                @endif
				<a href="{{ route('register') }}">Chưa có tài khoản ›</a>
			</div>
		</div>
	</div>

	<div class="col-md-4 hidden-xs hidden-sm">
		<div class="content-phu" style="opacity: 1; display: block">
			<div class="item" style="background-image:url({{ asset('web/client/images/img.jpg') }});">
				<div class="content">
					<div class="tt">Sàn Acc Game</div>
					<div class="des">Website hỗ trợ game thủ mua bán tài khoản game an toàn tránh lừa đảo!</div>
				</div> 
				<div class="gra"></div>
			</div> 
		</div> 
	</div>
@endsection