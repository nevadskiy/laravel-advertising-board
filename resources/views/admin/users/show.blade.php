@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.users._nav')

        <div class="d-flex flew-row mb-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary mr-1">Edit</a>
            @if ($user->isWait())
                <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Verify</button>
                </form>
            @endif
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>

        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>ID</th><td>{{ $user->id }}</td>
            </tr>
            <tr>
                <th>Name</th><td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Email</th><td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Status </th>
                <td>
                    @if ($user->isWait())
                        <span class="badge badge-secondary">Waiting</span>
                    @elseif ($user->isActive())
                        <span class="badge badge-primary">Active</span>
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
