<?php

namespace App\Http\Controllers\Cabinet;

use App\Entity\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Services\Adverts\FavoriteService;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * @var FavoriteService
     */
    private $service;

    /**
     * FavoriteController constructor.
     * @param FavoriteService $service
     */
    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $adverts = Advert::favoredByUser(Auth::user())->orderByDesc('id')->paginate(20);

        return view('cabinet.favorites.index', compact('adverts'));
    }

    /**
     * @param Advert $advert
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Advert $advert)
    {
        try {
            $this->service->remove(Auth::id(), $advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.favorites.index');
    }
}
