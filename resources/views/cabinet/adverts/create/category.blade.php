@extends('layouts.app')

@section('content')
    @include('cabinet._nav')

    <div class="container">
        <p>Choose category:</p>

        @include('cabinet.adverts.create._categories', compact('categories'))
    </div>
@endsection