<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfileOnly;

class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware(FilledProfileOnly::class);
    }

    public function index()
    {
        return view('cabinet.adverts.index');
    }
}
