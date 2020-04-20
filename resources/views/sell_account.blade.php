@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="row justify-content-center">
	        <div class="col-md-8">
	            <div class="card">
	                <div class="card-header">{{ __('Thông tin tài khoản muốn bán') }}</div>

	                <div class="card-body">
	                    <form method="POST" action="{{ route('account.store') }}" enctype="multipart/form-data">
	                        @csrf

	                        <div class="form-group row">
	                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

	                            <div class="col-md-6">
	                                <input id="name" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

	                                @error('username')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

	                            <div class="col-md-6">
	                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="password">

	                                @error('password')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Số điện thoại liên lạc') }}</label>

	                            <div class="col-md-6">
	                                <input id="contact_phone" type="number" class="form-control @error('contact_phone') is-invalid @enderror" name="contact_phone" value="{{ old('contact_phone') }}" required>

	                                @error('contact_phone')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="link" class="col-md-4 col-form-label text-md-right">{{ __('Trang cá nhân của bạn') }}</label>

	                            <div class="col-md-6">
	                                <input id="contact_link" type="url" class="form-control @error('contact_link') is-invalid @enderror" name="contact_link" value="{{ old('contact_link') }}" required placeholder="Facebook, Zalo, Instagram, Twitter...">

	                                @error('contact_link')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Giá muốn bán') }}</label>

	                            <div class="col-md-6">
	                                <input id="price" type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required autocomplete="price" autofocus>

	                                @error('price')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Mô tả tài khoản') }}</label>

	                            <div class="col-md-6">
	                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="10">{{ old('description') }}</textarea>

	                                @error('description')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
	                            </div>
	                        </div>

	                        <div id="game" class="form-group row">
	                            <label for="game" class="col-md-4 col-form-label text-md-right">{{ __('Game') }}</label>

	                            <div class="col-md-6">
	                                <select name="game_id" id="selectGame" class="form-control" onchange="changeGame(event)">
	                                	@foreach($games as $game)
											<option value="{{ $game['id'] }}">{{ $game['name'] . ' - phí ' . $game['fee'] }}</option>
	                                	@endforeach
	                                </select>
	                            </div>
	                        </div>

	                        @php
	                        	$infos = explode('|', json_decode($games->first(), true)['info']);
	                        @endphp
	                        @for($i = count($infos)-1; $i >= 0; $i--)
								<div class="form-group row info">
		                            <label for="{{ $infos[$i] }}" class="col-md-4 col-form-label text-md-right">{{ $infos[$i] }}</label>
		                            <div class="col-md-6">
		                                <input type="text" class="form-control" name="info[]" required>
		                            </div>
		                        </div>
	                        @endfor

	                        <div class="form-group row">
	                            <label for="pictures" class="col-md-4 col-form-label text-md-right">{{ __('Hình tài khoản') }}</label>

	                            <div class="col-md-6">
	                                <input class="form-control @error('pictures') is-invalid @enderror" type="file" name="pictures[]" onchange="upHinh($event)" accept="image/*" multiple required>

	                                @error('pictures')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right"><b>{{ __('Lưu ý') }}</b></label>

	                            <div class="col-md-6">
	                                <p>Vui lòng điền đúng các trường ở trên và chỉ được điền 1 lần, nếu phát hiện spam bán acc giả, hệ thống sẽ xóa tài khoản của bạn!</p>
	                            </div>
	                        </div>

	                        <div class="form-group row mb-0">
	                            <div class="col-md-6 offset-md-4">
	                                <button type="submit" class="btn btn-primary">
	                                    {{ __('Hoàn tất') }}
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
		@if(Session::has('error'))
			alert('{{ Session::get('error') }}');
		@endif

		function changeGame(e) {
			$('.info').remove();

            var games = {!! $games !!};
            var game = games.filter(function(value) {
            	return value.id == e.target.value;
            })[0];
			var need_info = game.info.split('|');
			need_info.forEach(function(ele, index) {
				var str = '<div class="form-group row info">\
                            <label for="'+ ele +'" class="col-md-4 col-form-label text-md-right">'+ ele +'</label>\
                            <div class="col-md-6">\
                                <input type="text" class="form-control" name="info[]" required>\
                            </div>\
                        </div>';
                $('#game').after(str);
			})
		}

		function upHinh(e) {
			console.log(e.target.files);
		}
	</script>
@endpush