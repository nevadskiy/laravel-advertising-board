@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.regions._nav')

        <form
                action="{{ route('admin.regions.store', ['parent' => $parent ? $parent->id : null]) }}"
                method="POST"
        >
            @csrf

            <div class="form-group">
                <label for="name" class="col-form-label">Name</label>
                <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        value="{{ old('name') }}"
                        required
                >
                @if ($errors->has('name'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label for="slug" class="col-form-label">Slug</label>
                <input
                        type="text"
                        id="slug"
                        name="slug"
                        class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                        value="{{ old('slug') }}"
                        required
                >
                @if ($errors->has('slug'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('slug') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
