<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfileOnly;
use Auth;

class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware(FilledProfileOnly::class);
    }

    public function index()
    {
        $adverts = Advert::forUser(Auth::user())->orderByDesc('id')->paginate(20);

        return view('cabinet.adverts.index', compact('adverts'));
    }
}
