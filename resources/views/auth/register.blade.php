@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Đăng ký') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <img src="{{ asset('web/client/images/logo.png') }}" alt="logo">
                        </div>
                    </div>
                    <br />
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Tên') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Địa chỉ E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row" id="div-phone">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Số điện thoại') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Mật khẩu') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Nhập lại mật khẩu') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Loại tài khoản muốn đăng ký') }}</label>

                            <div class="col-md-6">
                                <select name="account_type" class="form-control">
                                	<option value="0">Mua</option>
                                	<option value="1">Bán</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="country" class="col-md-4 col-form-label text-md-right">{{ __('Nơi ở hiện tại') }}</label>

                            <div class="col-md-6">
                                <select name="country" class="form-control" onchange="onChangeCountry(event)">
                                    <option value="0">Việt Nam</option>
                                    <option value="1">Foreign Customer (Khách nước ngoài)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="warn" class="col-md-4 col-form-label text-md-right"><b>{{ __('Lưu ý') }}</b></label>

                            <div class="col-md-6">
                                <p>Vui lòng điền đúng các trường ở trên nếu không, mọi hậu quả sau này bạn tự chịu trách nhiệm (quên mật khẩu, hỗ trợ khi mua tài khoản, hỗ trợ nạp thẻ...)</p>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Đăng ký') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('style')
    <style>
        body {
            background: url(../web/client/images/bg-p.jpg) center top no-repeat #0f1113;
            background-size: 100% auto;
        }
    </style>
@endpush
@push('script')
<script>
    function onChangeCountry(e) {
        if (e.target.value == 1) {
            $('#div-phone').hide();
        } else if (e.target.value == 0) {
            $('#div-phone').show();
        }
    }
</script>
@endpush