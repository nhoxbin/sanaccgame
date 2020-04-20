@extends('layouts.app')
@section('content')
<div class="sl-dtprod">
	<div class="container">
		<div class="sl-dtprmain">
			<div class="sa-lsnmain clearfix">
				<ul class="sa-brea">
					<li><a href="https://accdotkich.com/" title="">Trang chủ</a></li>
					<li class="active"><a href="#" title="">Lịch sử giao dịch</a></li>
				</ul>

				@if(Auth::user()->type == 0)
					<!-- Modal -->
					<div id="modalReason" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<!-- Modal content -->
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									{{-- <h4 class="modal-title">Modal Header</h4> --}}
								</div>
								<div class="modal-body">
									<textarea id="reason" class="form-control" placeholder="Nhập vào lý do hủy (nếu có)"></textarea>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info" data-dismiss="modal">Đóng</button>
									<button type="button" class="btn btn-danger" onclick="ConfirmOrRejectAccount('reject', account_id)" data-dismiss="modal">Hủy hóa đơn</button>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header">Lịch sử mua tài khoản</div>
						<div class="card-body">
							<span>Chú ý: khi nhấn xác nhận ACC sẽ ko khiếu nại được nữa vui lòng kiểm tra nick và đổi hết thông tin trước khi nhấn xác nhận!!</span>
							<div class="table-responsive">
								<table id="tblBuy" class="table table-hover">
									<thead>
										<tr>
											<td>Ngày</td>
											<td>Mã GD</td>
											<td>Tên người bán</td>
											<td>Game</td>
											<td>Tài khoản</td>
											<td>Mật khẩu</td>
											<td>Đơn Giá</td>
											<td>TT liên lạc</td>
											<td>Hành động</td>
										</tr>
									</thead>
									<tbody>
										@foreach($user->buy_bills as $bill)
											<tr>
												<td>{{ $bill->created_at }}</td>
												<td>{{ $bill->id }}</td>
												<td>{{ $bill->account->user->name }}</td>
												<td>{{ $bill->account->game->name }}</td>
												<td>{{ $bill->account->username }}</td>
												<td>{{ $bill->account->password }}</td>
												<td>{{ number_format($bill->account->price) }} đ</td>
												<td>{!! 'Số điện thoại: ' . $bill->account->contact_phone . '<br />Trang cá nhân: ' . $bill->account->contact_link !!}</td>
												<td>
													<div class="btn-group btn-group-sm">
														<a class="btn btn-info" href="{{ route('account.show', $bill->account->id) }}">Xem ACC</a>
														@if($bill->account->client_status == 1)
															<button type="button" class="btn btn-success" onclick="ConfirmOrRejectAccount('confirm', {{ $bill->account->id }})">Xác nhận</button>
															<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalReason" onclick="account_id={{ $bill->account->id }}">Báo cáo</button>
														@endif
													</div>
												</td>
											</tr>
										@endforeach
								    </tbody>
								</table>
							</div>
						</div>
					</div>
				@else
					<div class="card">
						<div class="card-header">Lịch sử mua tài khoản</div>
						<div class="card-body">
							<div class="table-responsive">
								<table id="tblBuy" class="table table-hover">
									<thead>
										<tr>
											<td>Ngày</td>
											<td>Mã GD</td>
											<td>Tên người mua</td>
											<td>Hành động</td>
										</tr>
									</thead>
									<tbody>
										@foreach($bills as $bill)
											<tr>
												<td>{{ $bill->created_at }}</td>
												<td>{{ $bill->id }}</td>
												<td>{{ $bill->user->name }}</td>
												<td>
													<a href="{{ route('account.show', $bill->account_id) }}" class="btn btn-sm btn-info" target="_blank">Xem Acc</a>
												</td>
											</tr>
										@endforeach
								    </tbody>
								</table>
							</div>
						</div>
					</div>
					<br />
					<div class="card">
						<div class="card-header">Lịch sử rút tiền</div>
						<div class="card-body">
							<div class="table-responsive">
								<table id="tblWithdraw" class="table table-hover">
									<thead>
										<tr>
											<td>Ngày</td>
											<td>Hình thức rút</td>
											<td>Số tiền</td>
											<td>Trạng thái</td>
										</tr>
									</thead>			
									<tbody>
										@foreach($user->withdraw_bills as $bill)
											<tr>
												<td>{{ $bill->created_at }}</td>
												<td>{{ $bill->type }}</td>
												<td>{{ number_format($bill->money) }} đ</td>
												<td>
													@php
														switch ($bill->confirm) {
															case -1:
																$status = 'Không thể rút tiền. Lý do: ' . $bill->reason;
																break;

															case 0;
																$status = 'Đang chờ xác nhận...';
																break;

															case 1:
																$status = 'Đã xác nhận.';
																break;
														}
														echo $status;
													@endphp
												</td>
											</tr>
										@endforeach
								    </tbody>
								</table>
							</div>
						</div>
					</div>
				@endif

				<br />

				<div class="card">
					<div class="card-header">Lịch sử nạp tiền</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="tblRecharge" class="table table-hover">
								<thead>
									<tr>
										<td>Ngày</td>
										<td>Hình thức nạp</td>
										<td>Số tiền</td>
										<td>Trạng thái</td>
										<td>Hành động</td>
									</tr>
								</thead>
								<tbody>
									@foreach($user->recharge_bills as $bill)
										<tr>
											<td>{{ $bill->created_at }}</td>
											<td>{{ $bill->type }}</td>
											<td>{{ number_format($bill->money) }}₫</td>
											<td>
												@switch ($bill->confirm)
													@case(-1)
														Không thể nạp tiền. Lý do: {{ $bill->reason }}
														@break

													@case(0)
														Đang chờ xác nhận...
														@break

													@case(1)
														Đã xác nhận.
														@break
												@endswitch
											</td>
											<td>
												@if($bill->type == 'card' && $bill->confirm == 0)
													<button class="btn btn-sm btn-success" onclick="location.href='{{ route('history.card.check', $bill->id) }}'">Kiểm tra thẻ nạp</button>
												@endif
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<br />

				<div class="card">
					<div class="card-header">Lịch sử chuyển tiền</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="tblTransfer" class="table table-hover">
								<thead>
									<tr>
										<td>Ngày</td>
										<td>Người chuyển</td>
										<td>Người nhận</td>
										<td>Số tiền</td>
									</tr>
								</thead>
								<tbody>
									@foreach($user->transfer_bills_receiver as $bill)
			                            <tr>
			                                <td>{{ $bill->created_at }}</td>
											<td>{{ $bill->from->name }}</td>
											<td>{{ $bill->to->name }}</td>
											<td>{{ number_format($bill->money) }}₫</td>
			                            </tr>
			                        @endforeach
			                        @foreach($user->transfer_bills_sender as $bill)
			                            <tr>
			                                <td>{{ $bill->created_at }}</td>
											<td>{{ $bill->from->name }}</td>
											<td>{{ $bill->to->name }}</td>
											<td>{{ number_format($bill->money) }}₫</td>
			                            </tr>
			                        @endforeach
							    </tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('style')
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endpush
@push('script')
	<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script>
		@if(session('success') || session('error'))
			alert('{{ session('success') ?? session('error') }}');
		@endif

		$(document).ready(function() {
		    $('#tblBuy').DataTable({
		    	order: [[ 0, 'desc' ]]
		    });

		    $('#tblWithdraw').DataTable({
		    	order: [[ 0, 'desc' ]]
		    });

		    $('#tblRecharge').DataTable({
		    	order: [[ 0, 'desc' ]]
		    });
		    
		    $('#tblTransfer').DataTable({
		    	order: [[ 0, 'desc' ]]
		    });
		});

		var account_id;

		function ConfirmOrRejectAccount(action, account_id) {
			var data = {
				action: action,
				_method: 'patch'
			};
			if (action === 'reject') {
				data.reason = $('#reason').val();
			}
			$.post(route('account.update', account_id), data, function(resp) {
				alert(resp);
				location.reload();
			});
		}
	</script>
@endpush