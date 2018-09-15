@extends('layouts.app')

@section('breadcrumbs', '')

@section('content')
<div class="container">
    <p><a href="{{ route('cabinet.adverts.create') }}" class="btn btn-success">Add Advert</a></p>

    <div class="card card-default mb-3">
        <div class="card-header">All categories</div>
        <div class="card-body pb-0">
            <div class="row">
                @foreach (array_chunk($categories, 3) as $chunk)
                    <div class="col-md-3">
                        <div class="list-unstyled">
                            @foreach ($chunk as $current)
                                <li>
                                    <a href="{{ route('adverts.index', [null, $current]) }}">
                                        {{ $current->name }}
                                    </a>
                                </li>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card card-default mb-3">
        <div class="card-header">All regions</div>
        <div class="card-body pb-0">
            <div class="row">
                @foreach (array_chunk($regions, 3) as $chunk)
                    <div class="col-md-3">
                        <div class="list-unstyled">
                            @foreach ($chunk as $current)
                                <li>
                                    <a href="{{ route('adverts.index', [null, $current]) }}">
                                        {{ $current->name }}
                                    </a>
                                </li>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
