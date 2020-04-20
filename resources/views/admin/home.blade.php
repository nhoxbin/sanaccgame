@extends('admin.layouts.master')
@section('heading-name', 'Blank')
@section('content')
<div class="row">
	@{{ text }}
</div>
@endsection
@push('script')
<script>
	new Vue({
		el: '#app',
		data: function() {
			return {
				text: 'Welcome to Admin Panel'
			}
		}
	})
</script>
@endpush