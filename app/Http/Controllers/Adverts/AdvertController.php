<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Advert\Advert;
use App\Entity\Advert\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\SearchRequest;
use App\Router\AdvertPath;
use App\Services\Adverts\AdvertSearchService;
use Gate;

class AdvertController extends Controller
{
    /**
     * @var AdvertSearchService
     */
    private $search;

    public function __construct(AdvertSearchService $search)
    {
        $this->search = $search;
    }

    public function index(SearchRequest $request, AdvertPath $path)
    {
        $category = $path->category;
        $region = $path->region;

        // TODO: get only categories & regions those has counts from elastic result

        $regions = $region
            ? $region->children()->orderBy('name')->getModels()
            : Region::root()->orderBy('name')->getModels();

        $categories = $category
            ? $category->children()->defaultOrder()->getModels()
            : Category::whereIsRoot()->defaultOrder()->getModels();

        $result = $this->search->search($category, $region, $request, 20, $request->get('page', 1));

        $adverts = $result->adverts;
        $categoriesCounts = $result->categoriesCounts;
        $regionsCounts = $result->regionsCounts;

        return view('adverts.index', compact(
            'category', 'region',
            'categories', 'regions',
            'categoriesCounts', 'regionsCounts',
            'adverts'
        ));
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
