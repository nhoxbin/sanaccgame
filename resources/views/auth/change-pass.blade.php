@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="row justify-content-center">
	        <div class="col-md-8">
	            <div class="card">
	                <div class="card-header">{{ __('Thông tin tài khoản muốn bán') }}</div>

	                <div class="card-body">
	                    <form method="POST" action="{{ route('password.change') }}">
	                        @csrf

	                        <div class="form-group row">
	                            <label for="old_password" class="col-md-4 col-form-label text-md-right">{{ __('Nhập mật khẩu cũ') }}</label>

	                            <div class="col-md-6">
	                                <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" value="{{ old('old_password') }}" required autofocus>

	                                @error('old_password')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Nhập mật khẩu mới') }}</label>

	                            <div class="col-md-6">
	                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required>

	                                @error('password')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Nhập lại mật khẩu mới') }}</label>

	                            <div class="col-md-6">
	                                <input type="password" class="form-control" name="password_confirmation" required>
	                            </div>
	                        </div>

	                        <div class="form-group row mb-0">
	                            <div class="col-md-6 offset-md-4">
	                                <button type="submit" class="btn btn-primary">
	                                    {{ __('Đổi password') }}
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

@push('script')
	<script>
		@if(session('success'))
			alert('{{ session('success') }}');
		@endif
	</script>
@endpush