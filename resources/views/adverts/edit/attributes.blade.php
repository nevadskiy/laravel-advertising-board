<div class="card mb-3">
    <div class="card-header">Characteristics</div>
    <div class="card-body pb-2">
        @foreach ($category->allAttributes() as $attribute)
            <div class="form-group">
                <label for="attribute_{{ $attribute->id  }}" class="col-form-label">{{ $attribute->name }}</label>

                @if ($attribute->isSelect())
                    <select
                            id="attribute_{{ $attribute->id }}"
                            name="attributes[{{ $attribute->id }}]"
                            class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                    >
                        @foreach ($attribute->variants as $variant)
                            <option value="{{ $variant }}"{{ $variant == old('addtibutes.' . $attribute->id) ? ' selected' : '' }}>
                                {{ $variant }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input
                            type="{{ $attribute->isNumber() ? 'text' : 'number' }}"
                            id="attributete_{{ $attribute->id }}"
                            name="attributes[{{ $attribute->id }}]"
                            value="{{ old('attributes.' . $attribute->id) }}"
                    >
                @endif

                @if ($errors->has('parent'))
                    <span class="invalid-feedback">
                                    <strong>{{ $errors->first('attributes.' . $attribute->id) }}</strong>
                                </span>
                @endif
            </div>
        @endforeach
    </div>
</div>

@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="?" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card mb-3">
                <div class="card-header">Characteristics</div>
                <div class="card-body pb-2">
                    @foreach ($category->allAttributes() as $attribute)
                        <div class="form-group">
                            <label for="attribute_{{ $attribute->id  }}" class="col-form-label">{{ $attribute->name }}</label>

                            @if ($attribute->isSelect())
                                <select
                                        id="attribute_{{ $attribute->id }}"
                                        name="attributes[{{ $attribute->id }}]"
                                        class="form-control{{ $errors->has('attributes.' . $attribute->id) ? ' is-invalid' : '' }}"
                                >
                                    @foreach ($attribute->variants as $variant)
                                        <option value="{{ $variant }}"{{ $variant == old('addtibutes.' . $attribute->id) ? ' selected' : '' }}>
                                            {{ $variant }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input
                                        type="{{ $attribute->isNumber() ? 'text' : 'number' }}"
                                        id="attributete_{{ $attribute->id }}"
                                        name="attributes[{{ $attribute->id }}]"
                                        value="{{ old('attributes.' . $attribute->id) }}"
                                >
                            @endif

                            @if ($errors->has('parent'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('attributes.' . $attribute->id) }}</strong>
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
@endsection