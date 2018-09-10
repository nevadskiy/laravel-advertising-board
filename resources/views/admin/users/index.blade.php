@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.users._nav')

        <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-3">Add User</a>

        <div class="card mb-3">
            <div class="card-header">Filter</div>
            <div class="card-body">
                <form action="?" method="GET">
                    <div class="row">
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label for="id" class="col-form-label">ID</label>
                                <input id="id" name="id" class="form-control" value="{{ request('id') }}">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="name" class="col-form-label">Name</label>
                                <input id="name" name="name" class="form-control" value="{{ request('name') }}">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="email" class="col-form-label">Email</label>
                                <input id="email" name="email" class="form-control" value="{{ request('email') }}">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="status" class="col-form-label">Role</label>
                                <select id="status" name="status" class="form-control">
                                    <option value=""></option>
                                    @foreach ($roles as $value => $label)
                                        <option value="{{ $value }}"{{ $value === request('status') ? ' selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="status" class="col-form-label">Status</label>
                                <select id="status" name="status" class="form-control">
                                    <option value=""></option>
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}"{{ $value === request('status') ? ' selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-ms-2">
                            <div class="form-group">
                                <label class="col-form-label">&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Role</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->isWait())
                            <span class="badge badge-secondary">Waiting</span>
                        @elseif ($user->isActive())
                            <span class="badge badge-primary">Active</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->isAdmin())
                            <span class="badge badge-secondary">Admin</span>
                        @else
                            <span class="badge badge-primary">User</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
@endsection
