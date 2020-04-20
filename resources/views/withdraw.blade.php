@extends('layouts.app')
@section('content')
	<div class="container">
		<div id="loading-icon" style="display: none;">
	        <img src="{{ asset('web/client/images/loading.gif') }}">
	    </div>

		<div class="row justify-content-center">
	        <div class="col-md-8">
	            <div class="card">
	                <div class="card-header">{{ __('Chọn 1 trong 3 hình thức nạp tiền') }}</div>

	                <div class="card-body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#bank">Rút ngân hàng</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#momo">Rút momo</a>
                            </li>
                        </ul>

	                	<div class="tab-content">
                            <div id="bank" class="tab-pane fade active show"><br />
                                <form method="POST" action="{{ route('withdraw.store') }}">
                                    @csrf

                                    <input type="hidden" name="type" class="type" value="bank">
                                    <div class="form-group row">
                                        <label for="bank_name" class="col-md-4 col-form-label text-md-right">{{ __('Tên và chi nhánh') }}</label>

                                        <div class="col-md-6">
                                            <input id="bank_name" type="text" class="form-control @error('bank_name') is-invalid @enderror" name="bank_name" value="{{ old('bank_name') ?? $bank['name'] }}" required autofocus>

                                            @error('bank_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="stk" class="col-md-4 col-form-label text-md-right">{{ __('Số tk') }}</label>

                                        <div class="col-md-6">
                                            <input id="stk" type="number" class="form-control @error('stk') is-invalid @enderror" name="stk" value="{{ old('stk') ?? $bank['stk'] }}" required>

                                            @error('stk')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="master_name" class="col-md-4 col-form-label text-md-right">{{ __('Tên chủ ngân hàng') }}</label>

                                        <div class="col-md-6">
                                            <input id="master_name" type="text" class="form-control @error('master_name') is-invalid @enderror" name="master_name" required value="{{ old('master_name') ?? $bank['master_name'] }}" placeholder="NGUYEN VAN A">

                                            @error('master_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="money" class="col-md-4 col-form-label text-md-right">{{ __('Số tiền muốn rút') }}</label>

                                        <div class="col-md-6">
                                            <input id="money" type="number" class="form-control @error('money') is-invalid @enderror" name="money" required value="{{ old('money') }}">

                                            @error('money')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div id="momo" class="tab-pane fade"><br />
                                <form method="POST" action="{{ route('withdraw.store') }}">
                                    @csrf

                                    <input type="hidden" name="type" class="type" value="momo">
                                    <div class="form-group row">
                                        <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Số điện thoại') }}</label>

                                        <div class="col-md-6">
                                            <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required autofocus>

                                            @error('phone_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="money" class="col-md-4 col-form-label text-md-right">{{ __('Số tiền muốn rút') }}</label>

                                        <div class="col-md-6">
                                            <input id="money" type="number" class="form-control @error('money') is-invalid @enderror" name="money" required value="{{ old('money') }}">

                                            @error('money')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button id="withdraw-btn" type="button" class="btn btn-primary">
                                        {{ __('Rút tiền') }}
                                    </button>
                                </div>
                            </div>
                        </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@endsection
@push('style')
	<style>
		.tab-content {
		    border-left: 1px solid #ddd;
		    border-right: 1px solid #ddd;
		    border-bottom: 1px solid #ddd;
		    border-radius: 0px 0px 5px 5px;
		    padding: 10px;
		}

		.nav-tabs {
		    margin-bottom: 0;
		}
	</style>
@endpush
@push('script')
	<script>
		@if(Session::has('error'))
			alert('{{ Session::get('error') }}');
		@elseif(Session::has('success'))
			alert('{{ Session::get('success') }}');
		@endif

        $(document).ready(function() {
            $('#withdraw-btn').on('click', function() {
                let form = $('.tab-pane.active').find('form');
                form.submit();
            });
        });
	</script>
@endpush