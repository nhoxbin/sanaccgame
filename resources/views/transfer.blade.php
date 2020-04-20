@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Chuyển tiền') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('transfer.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="to" class="col-md-4 col-form-label text-md-right">{{ __('ID muốn chuyển') }}</label>

                            <div class="col-md-6">
                                <input id="to" type="number" class="form-control @error('to') is-invalid @enderror" name="to" value="{{ old('to') }}" required autofocus>

                                @error('to')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="money" class="col-md-4 col-form-label text-md-right">{{ __('Số tiền') }}</label>

                            <div class="col-md-6">
                                <input id="money" type="number" class="form-control @error('money') is-invalid @enderror" name="money" required>

                                @error('money')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Chuyển') }}
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
        @if(Session::has('success') || Session::has('error'))
            alert('{{ Session::get('success') ?? Session::get('error') }}');
        @endif
    </script>
@endpush