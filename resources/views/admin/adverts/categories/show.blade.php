@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.adverts.categories._nav')

        <div class="d-flex flew-row mb-3">
            <a href="{{ route('admin.adverts.categories.edit', $category) }}" class="btn btn-primary mr-1">Edit</a>
            <form action="{{ route('admin.adverts.categories.destroy', $category) }}" method="POST" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>

        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>Name</th>
                <td>{{ $category->name }}</td>
            </tr>
            <tr>
                <th>Slug</th>
                <td>{{ $category->slug }}</td>
            </tr>
            </tbody>
        </table>

        <a href="{{ route('admin.adverts.categories.attributes.create', $category) }}" class="btn btn-success mb-3">Add
            attribute</a>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Sort</th>
                <th>Name</th>
                <th>Type</th>
                <th>Required</th>
            </tr>
            </thead>
            <tbody>

            <tr><th colspan="4">Parent attributes</th></tr>
            @forelse ($parentAttributes as $attribute)
                <tr>
                    <td>{{ $attribute->sort }}</td>
                    <td>{{ $attribute->name }}</td>
                    <td>{{ $attribute->type }}</td>
                    <td>{{ $attribute->required ? 'Yes' : '' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">None</td></tr>
            @endforelse

            <tr><th colspan="4">Own attributes</th></tr>
            @forelse ($attributes as $attribute)
                <tr>
                    <td>{{ $attribute->sort }}</td>
                    <td>
                        <a href="{{ route('admin.adverts.categories.attributes.show', [$category, $attribute]) }}">
                            {{ $attribute->name }}
                        </a>
                    </td>
                    <td>{{ $attribute->type }}</td>
                    <td>{{ $attribute->required ? 'Yes' : '' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">None</td></tr>
            @endforelse

            </tbody>
        </table>
    </div>
@endsection
