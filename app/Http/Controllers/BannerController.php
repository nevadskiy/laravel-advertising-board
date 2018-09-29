<?php

namespace App\Http\Controllers;

use App\Entity\Banner\Banner;
use App\Services\Banners\BannerService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * @var BannerService
     */
    private $service;

    /**
     * BannerController constructor.
     * @param BannerService $service
     */
    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function get(Request $request)
    {
        $format = $request['format'];
        $category = $request['category'];
        $region = $request['region'];

        if (!$banner = $this->service->getRandomForView($category, $region, $format)) {
            return '';
        }

        return view('banner.get', compact('banner'));
    }

    /**
     * @param Banner $banner
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function click(Banner $banner)
    {
        $this->service->click($banner);
        return redirect($banner->url);
    }
}
