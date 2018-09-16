@extends('layouts.app')

@section('content')
    @include('cabinet._nav')

    <div class="container">
        @if ($region)
            <p>
                <a href="{{ route('cabinet.adverts.create.advert', [$category, $region]) }}" class="btn btn-success">
                    Add advert for {{ $region->name }}
                </a>
            </p>
        @else
            <p>
                <a href="{{ route('cabinet.adverts.create.advert', $category) }}" class="btn btn-success">
                    Add advert for all regions
                </a>
            </p>
        @endif

        <p>Or choose nested region:</p>

        <ul>
            @foreach ($regions as $current)
                <li>
                    <a href="{{ route('cabinet.adverts.create.region', [$category, $current]) }}">
                        {{ $current->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection