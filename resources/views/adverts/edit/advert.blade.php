@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('cabinet.adverts.create.advert.store', [$category, $region]) }}">
            @csrf
            <div class="card mb-3">
                <div class="card-header">Common</div>
                <div class="card-body pb-2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" class="col-form-label">Title</label>
                                <input
                                        id="title"
                                        class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                        type="text"
                                        name="title"
                                        value="{{ old('title') }}"
                                        required
                                >
                                @if ($errors->has('title'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="col-form-label">Price</label>
                                <input
                                        id="price"
                                        class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}"
                                        type="number"
                                        name="price"
                                        value="{{ old('price') }}"
                                        required
                                >
                                @if ($errors->has('price'))
                                    <span class="invalid-feedback"><strong>{{ $errors->first('price') }}</strong></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="col-form-label">Address</label>
                        <input
                                id="address"
                                class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                type="number"
                                name="address"
                                value="{{ old('address', $region->getAddress()) }}"
                                required
                        >
                        @if ($errors->has('address'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('address') }}</strong></span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="content" class="col-form-label">Content</label>
                        <textarea
                                id="content"
                                class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                                name="content"
                                rows="10"
                                required
                        >{{ old('content') }}</textarea>
                        @if ($errors->has('content'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('content') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection