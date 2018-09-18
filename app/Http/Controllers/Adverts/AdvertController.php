<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Router\AdvertPath;
use Gate;

class AdvertController extends Controller
{
    public function index(AdvertPath $path)
    {
        $query = Advert::active()->with(['category', 'region'])->orderByDesc('published_at');

        if ($category = $path->category) {
            $query->forCategory($category);
        }

        if ($region = $path->region) {
            $query->forRegion($region);
        }

        $regions = $region
            ? $region->children()->orderBy('name')->getModels()
            : Region::root()->orderBy('name')->getModels();

        $categories = $category
            ? $category->children()->defaultOrder()->getModels()
            : Category::whereIsRoot()->defaultOrder()->getModels();

        $adverts = $query->paginate(20);

        return view('adverts.index', compact('category', 'region', 'categories', 'regions', 'adverts'));
    }

    public function show(Advert $advert)
    {
        if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(403);
        }

        return view('adverts.show', compact('advert'));
    }

    public function phone(Advert $advert): string
    {
        if (!$advert->isActive() || !Gate::allows('show-advert', $advert)) {
            abort(403);
        }

        return $advert->user->phone;
    }
}
