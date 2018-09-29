<?php

namespace App\Http\Controllers\Cabinet\Banners;

use App\Entity\Advert\Category;
use App\Entity\Banner\Banner;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\CreateRequest;
use App\Services\Banners\BannerService;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    /**
     * @var BannerService
     */
    private $service;

    /**
     * CreateController constructor.
     * @param BannerService $service
     */
    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category()
    {
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();

        return view('cabinet.banners.create.category', compact('categories'));
    }

    /**
     * @param Category $category
     * @param Region|null $region
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function region(Category $category, Region $region = null)
    {
        $regions = Region::where('parent_id', $region ? $region->id : null)->orderBy('name')->get();

        return view('cabinet.banners.create.region', compact('category', 'region', 'regions'));
    }

    /**
     * @param Category $category
     * @param Region|null $region
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function banner(Category $category, Region $region = null)
    {
        $formats = Banner::formatsList();

        return view('cabinet.banners.create.banner', compact('category', 'region', 'formats'));
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
            $banner = $this->service->create(
                Auth::user(),
                $category,
                $region,
                $request
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }
}
