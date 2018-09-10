@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.adverts.categories._nav')

        <form action="{{ route('admin.adverts.categories.attributes.store', $category) }}" method="POST">
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
                <label for="sort" class="col-form-label">Sort</label>
                <input
                        type="text"
                        id="sort"
                        name="sort"
                        class="form-control{{ $errors->has('sort') ? ' is-invalid' : '' }}"
                        value="{{ old('sort') }}"
                        required
                >
                @if ($errors->has('sort'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('sort') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label for="type" class="col-form-label">Type</label>
                <select id="type" name="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}">
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}"{{ $value === old('type') ? ' selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if ($errors->has('type'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('type') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <label for="variants" class="col-form-label">Variants</label>
                <textarea name="variants" id="variants" class="form-control{{ $errors->has('variants') ? ' is-invalid' : '' }}">
                    {{ old('variants') }}
                </textarea>
                @if ($errors->has('variants'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('variants') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <input type="hidden" name="required" value="0">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="required"{{ old('required') ? ' checked' : '' }}> Required
                    </label>
                </div>
                @if ($errors->has('required'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('required') }}</strong></span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
