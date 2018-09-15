@extends('layouts.app')

@section('content')
    <div class="container">
        <p><a href="{{ route('cabinet.adverts.create') }}" class="btn btn-success">Add Advert</a></p>

        @if ($categories)
            <div class="card card-default mb-3">
                <div class="card-header">
                    @if ($category)
                        Categories of {{ $category->name }}
                    @else
                        Categories
                    @endif
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        @foreach (array_chunk($categories, 3) as $chunk)
                            <div class="col-md-3">
                                <div class="list-unstyled">
                                    @foreach ($chunk as $current)
                                        <li>
                                            <a href="{{ route('adverts.index', [$region, $current]) }}">
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
        @endif

        @if ($regions)
            <div class="card card-default mb-3">
                <div class="card-header">
                    @if ($region)
                        Regions of {{ $region->name }}
                    @else
                        Regions
                    @endif
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        @foreach (array_chunk($regions, 3) as $chunk)
                            <div class="col-md-3">
                                <div class="list-unstyled">
                                    @foreach ($chunk as $current)
                                        <li>
                                            <a href="{{ route('adverts.index', [$current, $category]) }}">
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
        @endif

        <div class="row">
            <div class="col-md-9">
                <div class="adverts-list">
                    @foreach ($adverts as $advert)
                        <div class="advert">
                            <div class="row">
                                <div class="col-md-3" style="height: 180px; background: #f6f6f6; border: 1px solid #ddd"></div>
                                <div class="col-md-9">
                                    <span class="float-right">
                                        {{ $advert->price }}
                                    </span>
                                    <div class="h4 mt-0">
                                        <a href="{{ route('adverts.show', $advert) }}">{{ $advert->title }}</a>
                                    </div>
                                    <p>Region: <a href="">{{ $advert->region ? $advert->region->name : 'All' }}</a></p>
                                    <p>Category: <a href="">{{ $advert->category->name }}</a></p>
                                    <p>Date: {{ $advert->created_at }}</p>
                                    <div>{{ \Illuminate\Support\Str::limit($advert->content, 200) }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{ $adverts->links() }}
            </div>

            <div class="col-md-3">
                <div style="height: 400px; background: #f6f6f6; border: 1px solid #ddd; margin-bottom: 20px"></div>
                <div style="height: 400px; background: #f6f6f6; border: 1px solid #ddd; margin-bottom: 20px"></div>
            </div>
        </div>
    </div>
@endsection