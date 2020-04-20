@extends('admin.layouts.master')
@section('heading-name', 'Lịch sử chuyển tiền')
@section('content')
<div class="container-fluid">
	<div class="card shadow mb-4">
	    <div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Lịch sử chuyển tiền</h6>
	    </div>
	    <div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
					    <tr>
							<th>Mã GD</th>
							<th>Tên người chuyển</th>
							<th>Tên người nhận</th>
							<th>Số tiền</th>
							<th>Ngày</th>
					    </tr>
					</thead>
					<tbody>
						@foreach($bills as $bill)
					  		<tr>
					  			<td>{{ $bill['id'] }}</td>
					  			<td>{{ $bill['from']['name'] }}</td>
					  			<td>{{ $bill['to']['name'] }}</td>
					  			<td>{{ number_format($bill['money']) . ' đ' }}</td>
					  			<td>{{ $bill['created_at'] }}</td>
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
<link href="{{ asset('libs/sb-admin2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@push('script')
<!-- Page level plugins -->
<script src="{{ asset('libs/sb-admin2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('libs/sb-admin2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
	var app = new Vue({
		el: '#app',
		data: function() {
			return {
				
			}
		},
		mounted() {
			$('#dataTable').DataTable();
		},
		methods: {
			
		}
	})
</script>
@endpush