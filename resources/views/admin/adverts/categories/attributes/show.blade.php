@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.adverts.categories._nav')

        <div class="d-flex flew-row mb-3">
            <a href="{{ route('admin.adverts.categories.attributes.edit', [$category, $attribute]) }}" class="btn btn-primary mr-1">Edit</a>
            <form action="{{ route('admin.adverts.categories.attributes.destroy', [$category, $attribute]) }}" method="POST" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>

        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>ID</th>
                <td>{{ $category->id}}</td>
            </tr>
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
    </div>
@endsection
