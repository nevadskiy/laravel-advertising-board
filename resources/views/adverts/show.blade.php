@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($advert->isDraft())
            <div class="alert alert-danger">
                It is a draft.
            </div>
            @if ($advert->reject_reason)
                <div class="alert alert-danger">
                    {{ $advert->reject_reason }}
                </div>
            @endif
        @endif

        @can('edit-own-advert', $advert)
            <div class="d-flex flex-row mb-3">
                <a href="{{ route('cabinet.adverts.edit', $advert) }}" class="btn btn-primary mr-1">Edit</a>
                <a href="{{ route('cabinet.adverts.photos', $advert) }}" class="btn btn-primary mr-1">Photos</a>
                <form method="POST" action="{{ route('cabinet.adverts.send', $advert) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Publish</button>
                </form>
                <form method="POST" action="{{ route('cabinet.adverts.destroy', $advert) }}" class="mr-1">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete</button>
                </form>
            </div>
        @endcan

        @can('moderate-advert', $advert)
            <div class="d-flex flex-row mb-3">
                <a href="{{ route('admin.adverts.edit', $advert) }}" class="btn btn-primary mr-1">Edit</a>
                <a href="{{ route('admin.adverts.photos', $advert) }}" class="btn btn-primary mr-1">Photos</a>
                <form method="POST" action="{{ route('admin.adverts.moderate', $advert) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Publish</button>
                </form>

                <a href="{{ route('admin.adverts.reject', $advert) }}" class="btn btn-danger mr-1">Reject</a>

                <form method="POST" action="{{ route('admin.adverts.destroy', $advert) }}" class="mr-1">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete</button>
                </form>
            </div>
        @endcan

        <div class="row">
            <div class="col-md-9">
                <span class="float-right">{{ $advert->price }}</span>
                <h1>{{ $advert->title }}</h1>

                <p>Region: <a href="">{{ $advert->region ? $advert->region->name : 'All' }}</a></p>
                <p>Category: <a href="">{{ $advert->category->name }}</a></p>
                <p>
                    Date: {{ $advert->created_at }}
                    @if ($advert->expires_at)
                        Expires: {{ $advert->created_at }}
                    @endif
                </p>
                <div>{!! nl2br(e($advert->content)) !!}</div>

                <p>Address: {{ $advert->address }}</p>
            </div>

            <p>Seller: {{ $advert->user->name }}</p>

            <p>
                <span class="btn btn-primary phone-button" data-source="{{ route('adverts.phone', $advert) }}">
                    <span class="fa fa-phone"></span><span class="number">Show Phone Number</span>
                </span>
            </p>
            <hr/>
            <div class="h3">Similar adverts</div>
            <div class="col-md-3" style="height: 180px; background: #f6f6f6; border: 1px solid #ddd"></div>
        </div>

        <div class="h3">Similar adverts</div>
        <div class="row">
            <div class="col-ms-6 col-md-4">
                <div class="card">
                    <div class="card-img-top"></div>
                </div>
            </div>
        </div>
    </div>
@endsection