<?php

use App\Entity\Advert\Category;
use App\Entity\Page;
use App\Entity\Region;
use App\Http\Router\AdvertPath;
use App\Http\Router\PagePath;

if (! function_exists('advert_path')) {
    /**
     * @param Region|null $region
     * @param Category|null $category
     * @return AdvertPath
     */
    function adverts_path(?Region $region, ?Category $category)
    {
        return app()->make(AdvertPath::class)
            ->withRegion($region)
            ->withCategory($category);
    }
}

if (! function_exists('page_path')) {
    /**
     * @param Page $page
     * @return PagePath
     */
    function page_path(Page $page)
    {
        return app()->make(PagePath::class)->withPage($page);
    }
}