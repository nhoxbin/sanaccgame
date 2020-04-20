@extends('admin.layouts.master')
@section('heading-name', 'Lịch sử rút tiền')
@section('content')
<div class="container-fluid">
	<div class="card shadow mb-4">
	    <div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Lịch sử rút tiền</h6>
	    </div>
	    <div class="card-body">
	    	<!-- Modal -->
			<div id="withdrawModal" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content -->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							{{-- <h4 class="modal-title">Modal Header</h4> --}}
						</div>
						<div class="modal-body">
							<textarea v-model="reason" class="form-control" placeholder="Nhập vào lý do hủy (nếu có)"></textarea>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-info" data-dismiss="modal">Đóng</button>
							<button type="button" class="btn btn-danger" onclick="app.action('reject', app.order_id)" data-dismiss="modal">Hủy hóa đơn</button>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
					    <tr>
							<th></th>
							<th>Ngày</th>
							<th>Mã GD</th>
							<th>Hình thức rút</th>
							<th>Số tiền</th>
							<th>Hành động</th>
					    </tr>
					</thead>
				</table>
			</div>
	    </div>
	</div>
</div>
@endsection
@push('css')
<!-- Custom styles for this page -->
<link href="{{ asset('web/admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<style>
	td.details-control {
	    background: url('{{ asset('web/admin/images/details_open.png') }}') no-repeat center center;
	    cursor: pointer;
	}
	tr.shown td.details-control {
	    background: url('{{ asset('web/admin/images/details_close.png') }}') no-repeat center center;
	}
</style>
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
				order_id: null,
				reason: null
			}
		},
		async mounted() {
			var table;
			await $.get(route('admin.datatables.withdraw_bills')).then(function(bills) {
				table = $('#dataTable').DataTable({
					data: bills,
					"columns": [
			            {
			                "className":      'details-control',
			                "orderable":      false,
			                "data":           null,
			                "defaultContent": ''
			            },
			            { "data": "created_at" },
			            { "data": "id" },
			            { "data": "payment_method" },
			            { "data": "money" },
			            { "data": "actions" }
			        ],
					"order": [[ 1, "desc" ]],
					
				});
			})
			var self = this;
			
			$('#dataTable tbody').on('click', 'td.details-control', function() {
		        var tr = $(this).closest('tr');
		        var row = table.row( tr );
		        if ( row.child.isShown() ) {
		            // This row is already open - close it
		            row.child.hide();
		            tr.removeClass('shown');
		        } else {
		            // Open this row
		            row.child( self.format(row.data()) ).show();
		            tr.addClass('shown');
		        }
		    });
		},
		methods: {
			format(d) {
				var tr = '';
				if (d.payment_method === 'momo') {
					tr = '<tr>'+
				            '<td>Số điện thoại:</td>'+
				            '<td>'+d.phone+'</td>'+
				        '</tr>';
				} else if (d.payment_method === 'bank') {
					tr = '<tr>'+
				            '<td>Tên ngân hàng:</td>'+
				            '<td>'+d.bank_name+'</td>'+
				        '</tr>'+
						'<tr>'+
				            '<td>STK:</td>'+
				            '<td>'+d.stk+'</td>'+
				        '</tr>'+
						'<tr>'+
				            '<td>Chủ tài khoản:</td>'+
				            '<td>'+d.master_name+'</td>'+
				        '</tr>';
				}
			    // `d` is the original data object for the row
			    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+tr+'</table>';
			},
			action(type, id) {
				let data = {
					action: type,
					_method: 'patch'
				};
				if (type === 'reject') {
					data.reason = this.reason;
				}
				$.ajax({
					url: route('admin.withdraw.update', id),
					method: 'post',
					data: data,
					success: function(resp) {
						alert(resp);
						location.reload();
					}
				});
			},
			deleteSim(id) {
				let conf = confirm('Bạn có chắc muốn xóa hóa đơn này chứ?');
				if (!conf) {
					return;
				}
				$.ajax({
					url: route('admin.withdraw.destroy', id),
					data: {
						_method: 'delete'
					},
					method: 'post',
					success: (resp) => {
						alert(resp);
						location.reload();
					}
				});
			}
		}
	})
</script>
@endpush