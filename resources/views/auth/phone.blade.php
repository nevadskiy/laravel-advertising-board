@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('login.phone') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="token" class="col-form-label">SMS Code</label>
                <input
                        type="text"
                        name="token"
                        class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}"
                        value="{{ old('token') }}"
                        required
                >
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Verify</button>
            </div>
        </form>
    </div>
@endsection
