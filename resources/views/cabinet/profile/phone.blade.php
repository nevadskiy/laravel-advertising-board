@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.users._nav')

        <form action="{{ route('cabinet.profile.phone.verify') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="token" class="col-form-label">SMS Code</label>
                <input
                        type="text"
                        id="token"
                        name="token"
                        class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}"
                        value="{{ old('token') }}"
                        required
                >
                @if ($errors->has('token'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('token') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Verify</button>
            </div>
        </form>
    </div>
@endsection
