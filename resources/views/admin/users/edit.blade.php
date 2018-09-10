@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.users._nav')

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="col-form-label">Name</label>
                <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        value="{{ old('name', $user->name) }}"
                        required
                >
                @if ($errors->has('name'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label for="email" class="col-form-label">Email</label>
                <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                        value="{{ old('email', $user->email) }}"
                        required
                >
                @if ($errors->has('email'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('email') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label for="email" class="col-form-label">Role</label>
                <select
                        id="role"
                        name="role"
                        class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}"
                >
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}"{{ $value === old('role', $user->role) ? ' selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('role'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('role') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
