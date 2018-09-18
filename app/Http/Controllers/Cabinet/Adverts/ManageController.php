<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfileOnly;
use App\Http\Requests\Adverts\AttributeRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Services\Adverts\AdvertService;

class ManageController extends Controller
{
    protected $advert;

    public function __construct(AdvertService $advert)
    {
        $this->advert = $advert;
        $this->middleware(FilledProfileOnly::class);
    }

    public function attributes(Advert $advert)
    {
        $this->checkAccess($advert);

        return view('adverts.edit.attributes', compact('advert'));
    }

    public function updateAttributes(AttributeRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);

        try {
            $this->advert->editAttributes($advert->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function photos(Advert $advert)
    {
        $this->checkAccess($advert);

        return view('adverts.edit.photos', compact('advert'));
    }

    public function updatePhotos(PhotosRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);

        try {
            $this->advert->addPhotos($advert->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function destroy(Advert $advert)
    {
        $this->checkAccess($advert);

        try {
            $this->advert->remove($advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->back();
    }

    public function moderate(Advert $advert)
    {
        $this->checkAccess($advert);

        try {
            $this->advert->moderate($advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->back();
    }

    public function checkAccess(Advert $advert): void
    {
        if (!\Gate::allows('manage-own-advert', $advert)) {
            abort(403);
        }
    }
}
