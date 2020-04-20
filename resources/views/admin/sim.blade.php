@extends('admin.layouts.master')
@section('heading-name', 'Các nhà mạng')
@section('content')
<div class="container-fluid">
	<div class="card shadow mb-4">
	    <div class="card-header py-3">
			{{-- <h6 class="m-0 font-weight-bold text-primary">Danh sách các gói</h6> --}}
			<div class="text-right">
				<button type="button" class="btn btn-primary" @@click="addSim" data-toggle="modal" data-target="#simModal">
					Thêm nhà mạng
				</button>
			</div>

			<!-- Modal thêm gói -->
			<div class="modal fade" id="simModal" tabindex="-1" role="dialog" aria-labelledby="crud" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="crud">Thêm nhà mạng</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form class="form-horizontal" onsubmit="return false;">
								<div class="form-group">
									<label class="control-label col-sm-12" for="name">Tên:</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" placeholder="Mobiphone, VinaPhone, Viettel, ..." v-model="simForm.name">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-12" for="discount">Chiết khấu:</label>
									<div class="col-sm-12">
										<input type="text" class="form-control" placeholder="10%" v-model="simForm.discount">
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
							<button type="button" class="btn btn-primary" @@click="actions(action)">Lưu</button>
						</div>
					</div>
				</div>
			</div>
	    </div>
	    <div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				  <thead>
				    <tr>
				      <th>Tên</th>
				      <th>Chiết khấu</th>
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
				action: 'add',
				simForm: {
					id: 0,
					name: '',
					discount: null
				},
				dataTable: null
			}
		},
		mounted() {
			this.getListSim();
		},
		methods: {
			addSim() {
				this.action = 'add';
				this.simForm.name = '';
				this.simForm.discount = '';
			},
			getListSim() {
				$.get(route('admin.datatables.sim')).then(list_sim => {
					this.dataTable = $('#dataTable').DataTable({
						data: list_sim,
				        columns: [
				            { data: 'name' },
				            { data: 'discount' },
				            { data: 'actions', orderable: false, searchable: false }
				        ]
					});
				});
			},
			actions() {
				let url, method;
				if (this.action === 'add') {
					url = route('admin.sim.store');
					method = 'post';
				} else {
					url = route('admin.sim.update', this.simForm.id);
					method = 'patch';
				}
				$.ajax({
					url: url,
					data: {
						name: this.simForm.name,
						discount: this.simForm.discount
					},
					method: method,
					success: (resp) => {
						alert(resp);
						location.reload();
					},
					error: function(resp) {
						alert('Vui lòng điền đầy đủ các trường đã cho!');
					}
				});
			},
			editSim(id) {
				this.action = 'edit';
				this.simForm.id = id;
				$.ajax({
					url: route('admin.sim.edit', id),
					success: (resp) => {
						this.simForm.name = resp.name;
						this.simForm.discount = resp.discount;
					}
				});
			},
			deleteSim(id) {
				let conf = confirm('Bạn có chắc muốn xóa Sim này chứ?');
				if (!conf) {
					return;
				}
				$.ajax({
					url: route('admin.sim.destroy', id),
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