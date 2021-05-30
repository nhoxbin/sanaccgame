@extends('layouts.app')
@section('title', 'Acc'. $account->game->sort_name . '#' . $account->id . ' | Giá: ' . number_format($account->price) . 'đ')
@section('content')
<div class="sl-dtprod">
    <div class="container">
        <div class="sl-dtprmain">
            <div class="sa-lsnmain clearfix">
                <ul class="sa-brea">
                    <li><a href="/">Trang Chủ</a></li>
                    <li class="active"><a>Acc {{ $account->game->sort_name }} #{{ $account->id }}</a></li>
                </ul>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="sa-ttactit clearfix">
                            <h1 class="sa-ttacc-tit">
                               ACC {{ $account->game->name }} #{{ $account->id }}</h1>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <a class="btn btn-warning" onclick="buy_now({{ $account->id }});" style="margin-bottom: 30px; border-radius: 0; font-size: 16px; font-weight: bold; padding: 20px; border: none; color: #fff; font-family: Lato,&#39;Helvetica Neue&#39;,Arial,Helvetica,sans-serif;">Mua Ngay Với Giá {{ number_format($account->price) }}đ</a>
                    </div>
                </div>
                <br />
				<b>
					@php
		                $account_info = array_combine(explode('|', $account->game->info), explode('|', $account->info));
		            @endphp
		            @foreach($account_info as $key => $info)
		                <li>{{ $key . ': ' . $info}}</li>
		            @endforeach
		            <li>Thông tin mô tả tài khoản: {{ $account->description }}</li>
				</b>
				<br />
                <ul class="sa-ttacc-tabs clearfix">
					<li class="active"><a href="#">Hình ảnh mô tả tài khoản</a></li>
                </ul>
                <div class="sa-ttacc-tcont tab-content">
                	<div class="text-center">
                		@php
                			$pictures = json_decode($account->pictures, true);
                		@endphp
                		@foreach($pictures as $picture)
							<a>
                                <img class="c-content-media-2 c-bg-img-center" src="{{ asset($picture) }}" width="100%">
                                <br>
                            </a>
                		@endforeach
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
	<script type="text/javascript">
		function buy_now(account_id) {
			swal({
				title: "Tài Khoản Số #"+account_id,
				text: "Bạn có chắc chắn muốn mua tài khoản này?",
				type: "info",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Có",
				cancelButtonText: "Không",
				closeOnConfirm: false,
				showLoaderOnConfirm: true
			}, function() {
				$.post(route('buy.store'), {
					"_token": '{{ csrf_token() }}',
				  	account_id: account_id
				}, function(data) {
				    if (data.success) {
				    	swal({
				    		title: data.message,
				    		type: 'success',
				    		text: 'Mời bạn đến trang lịch sử giao dịch để xem tài khoản!'
				    	}, function() {
					    	location.href = route('history.index');
				    	});
				    } else {
						swal({
							title : "Có lỗi xảy ra",
							type: "error",
							text: data.message
						}, function() {
							location.reload();
						});
				    }
				}).fail(function(err) {
					if (err.status == 401) {
						alert('Vui lòng đăng nhập!');
						location.reload();
					}
				});
			});
		}

		function copyToClipboard(element) {
			var $temp = $('<input>');
			$("body").append($temp);
			$temp.val($(element).text()).select();
			document.execCommand('copy');
			$temp.remove();
			swal({
				type: 'success',
				title: 'Đã Copy!',
				showConfirmButton: false,
				timer: 1000
			});
		}
	</script>
@endpush
