<?php

use App\Entity\Advert\Category;
use App\Entity\Region;
use App\Http\Router\AdvertPath;

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
