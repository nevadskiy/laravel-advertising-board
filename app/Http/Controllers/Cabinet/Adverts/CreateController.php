<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Advert\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\CreateRequest;
use App\Services\Adverts\AdvertService;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    /**
     * @var AdvertService
     */
    private $service;

    /**
     * CreateController constructor.
     * @param AdvertService $service
     */
    public function __construct(AdvertService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category()
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();

        return view('cabinet.adverts.create.category', compact('categories'));
    }

    /**
     * @param Category $category
     * @param Region|null $region
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function region(Category $category, Region $region = null)
    {
        $regions = Region::where('parent_id', $region ? $region->id : null)->orderBy('name')->get();

        return view('cabinet.adverts.create.region', compact('category', 'region', 'regions'));
    }

    /**
     * @param Category $category
     * @param Region|null $region
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function advert(Category $category, Region $region = null)
    {
        return view('cabinet.adverts.create.advert', compact('category', 'region'));
    }

    /**
     * @param CreateRequest $request
     * @param Category $category
     * @param Region|null $region
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function store(CreateRequest $request, Category $category, Region $region = null)
    {
        try {
            $advert = $this->service->create(
                Auth::id(),
                $category->id,
                $region ? $region->id : null,
                $request
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }
}
