@extends('layouts.app')

@section('content')
    <div class="container">
        @include('admin.regions._nav')

        <a href="{{ route('admin.regions.create') }}" class="btn btn-success mb-3">Add region</a>

        @include('admin.regions._list')

        {{ $regions->links() }}
    </div>
@endsection
