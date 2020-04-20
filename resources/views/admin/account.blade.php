@extends('admin.layouts.master')
@section('heading-name', 'Tài khoản game ' . $game['name'])
@section('content')
<div class="container-fluid">
	<div class="card shadow mb-4">
	    <div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Danh sách các ACC {{ $game['name'] }}</h6>
	    </div>
	    <div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				  <thead>
				    <tr>
				      <th>ID</th>
				      <th>Tài khoản</th>
				      <th>Mật khẩu</th>
				      <th>Giá</th>
				      <th>Trạng thái</th>
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
				action: 'save',
				accountForm: {
					game_id: {{ $game['id'] }},
					account: null
				},
				dataTable: null
			}
		},
		mounted() {
			this.getListAccount();
		},
		methods: {
			getListAccount() {
				$.get(route('admin.datatables.game.account', this.accountForm.game_id)).then(list_game => {
					this.dataTable = $('#dataTable').DataTable({
						data: list_game,
				        columns: [
				            { data: 'id' },
				            { data: 'username', searchable: false },
				            { data: 'password', searchable: false },
				            { data: 'price' },
				            { data: 'status', searchable: false },
				            { data: 'actions', orderable: false, searchable: false }
				        ]
					});
				});
			},
			actionAccount(action, id) {
				$.post(route('admin.game.account.update', [this.accountForm.game_id, id]), {
					action: action,
					_method: 'patch'
				}).then(function(resp) {
					alert(resp);
					location.reload();
				});
			},
			deleteAccount(id) {
				let conf = confirm('Bạn có chắc muốn xóa tài khoản này chứ?');
				if (!conf) {
					return;
				}
				$.ajax({
					url: route('admin.game.account.destroy', [this.accountForm.game_id, id]),
					data: {
						_method: 'delete'
					},
					method: 'post',
					success: function(resp) {
						if (resp === 'ok') {
							alert('Xóa thành công!');
							location.reload();
						}
					}
				});
			}
		}
	})
</script>
@endpush