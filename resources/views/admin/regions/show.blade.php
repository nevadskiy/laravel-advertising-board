@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.regions._nav')

        <div class="d-flex flew-row mb-3">
            <a href="{{ route('admin.regions.edit', $region) }}" class="btn btn-primary mr-1">Edit</a>
            <form action="{{ route('admin.regions.destroy', $region) }}" method="POST" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>

        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>ID</th>
                <td>{{ $region->id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $region->name }}</td>
            </tr>
            <tr>
                <th>Slug</th>
                <td>{{ $region->slug }}</td>
            </tr>
            </tbody>
        </table>

        <a href="{{ route('admin.regions.create', ['parent' => $region->id]) }}" class="btn btn-success mb-3">Add region</a>

        @if(count($regions))
            @include('admin.regions._list')
        @endif
    </div>
@endsection
