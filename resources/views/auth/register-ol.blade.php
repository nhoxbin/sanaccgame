@extends('layouts.auth')

@section('content')
	<div class="col-md-6 col-md-offset-3">
		<div class="content-main">
			<a href="./" class="logo"><img src="{{ asset('web/client/images/logo.png') }}" alt="logo"></a>
			<div class="tt">Đăng Ký Thành Viên Sàn Acc Game</div>
			<form method="POST" action="{{ route('register') }}" novalidate>
				@csrf

				<div class="input-info @error('name') input-alert @enderror">
					<input type="text" class="input-pop" placeholder="Tên" style="height: 50px; font-size: 16px" name="name" value="{{ old('name') }}" autofocus>
					@error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
				</div>

				<div class="input-info @error('email') input-alert @enderror">
					<input type="text" class="input-pop" placeholder="Địa chỉ E-Mail" style="height: 50px; font-size: 16px" name="email" value="{{ old('email') }}" required>
					@error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
				</div>

				<div class="input-info @error('phone') input-alert @enderror" id="div-phone">
					<input type="text" class="input-pop" placeholder="Số điện thoại (e.g. 0967204953)" maxlength="10" name="phone" style="height: 50px; font-size: 16px" autofocus required>
					@error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
				</div>

				<div class="input-info @error('password') input-alert @enderror">
					<input type="password" class="input-pop" placeholder="Mật khẩu" name="password" style="height: 50px; font-size: 16px" required>
					@error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
				</div>

				<div class="input-info">
					<input type="password" class="input-pop" placeholder="Nhập lại mật khẩu" name="password_confirmation" style="height: 50px; font-size: 16px">
				</div>

				<div class="input-info">
					<select name="account_type" class="form-control">
                    	<option value="0">Tài khoản mua</option>
                    	<option value="1">Tài khoản bán</option>
                    </select>
				</div>

				<br />

				<div class="input-info">
					<select name="country" class="form-control" onchange="onChangeCountry(event)">
                        <option value="0">Việt Nam</option>
                        <option value="1">Foreign Customer (Khách nước ngoài)</option>
                    </select>
				</div>

				<button type="submit" class="btn btn-info btn-block" style="font-size: 16px; padding: 17px 0px; margin-bottom: -15px; margin-top: 14px;"><span>Đăng ký</span></button>
			</form>

			<p id="error-input" style="color: red; text-align: center"></p>

			<center style="margin-top: 40px;"><p style="font-size: 14px; padding: 0">Đã có tài khoản? <a href="{{ route('login') }}" style="color: #1c8f90">Đăng nhập ngay</a></p></center>
		</div> 
	</div>

	<script>
	    function onChangeCountry(e) {
	        if (e.target.value == 1) {
	            $('#div-phone').hide();
	        } else if (e.target.value == 0) {
	            $('#div-phone').show();
	        }
	    }
	</script>
@endsection