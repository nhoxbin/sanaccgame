@extends('admin.layouts.master')
@section('heading-name', 'Lịch sử mua')
@section('content')
<div class="container-fluid">
	<div class="card shadow mb-4">
	    <div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Lịch sử mua</h6>
	    </div>
	    <div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
					    <tr>
							<th>Ngày</th>
							<th>Mã GD</th>
							<th>Tên người mua</th>
							<th>Tên người bán</th>
							<th>Mua ACC số</th>
							<th>Game</th>
							<th>Hành động</th>
					    </tr>
					</thead>
					<tbody>
						@foreach($buy_bills as $bill)
					  		<tr>
					  			<td>{{ $bill['created_at'] }}</td>
					  			<td>{{ $bill['id'] }}</td>
					  			<td>{{ $bill['user']['name'] }}</td>
					  			<td>{{ $bill['account']['user']['name'] }}</td>
					  			<td>{{ $bill['account']['id'] }}</td>
					  			<td>{{ $bill['account']['game']['name'] }}</td>
					  			<td>
									<a href="{{ route('account.show', $bill['account_id']) }}" class="btn btn-sm btn-info" target="_blank">Xem Acc</a>
					  			</td>
					  		</tr>
						@endforeach
					</tbody>
				</table>
			</div>
	    </div>
	</div>
</div>
@endsection
@push('css')
<!-- Custom styles for this page -->
<link href="{{ asset('web/admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@push('script')
<!-- Page level plugins -->
<script src="{{ asset('web/admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('web/admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
	var app = new Vue({
		el: '#app',
		data: function() {
			return {
				reason: '',
				order_id: ''
			}
		},
		mounted() {
			$('#dataTable').DataTable();
		},
		methods: {
			action(type, id) {
				$.ajax({
					url: route('admin.buy.update', id),
					data: {
						reason: this.reason,
						action: type,
						_method: 'patch'
					},
					method: 'post',
					success: function(resp) {
						alert(resp);
						location.reload();
					}
				});
			}
		}
	})
</script>
@endpush