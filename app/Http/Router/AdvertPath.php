<?php

namespace App\Http\Router;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use Cache;
use Illuminate\Contracts\Routing\UrlRoutable;

class AdvertPath implements UrlRoutable
{
    /**
     * @var
     */
    public $region;

    /**
     * @var
     */
    public $category;

    /**
     * __construct() will conflict with laravel DI
     * so should this approach to be used
     *
     * @param Region|null $region
     * @return AdvertPath
     */
    public function withRegion(?Region $region): self
    {
        $clone = clone $this;
        $clone->region = $region;

        return $clone;
    }

    /**
     * __construct() will conflict with laravel DI
     * so should this approach to be used
     *
     * @param Category|null $category
     * @return AdvertPath
     */
    public function withCategory(?Category $category): self
    {
        $clone = clone $this;
        $clone->category = $category;

        return $clone;
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        $segments = [];

        if ($this->region) {
            $segments[] = Cache::tags(Region::class)
                ->rememberForever('region:' . $this->region->id, function () {
                    return $this->region->getPath();
                });
        }

        if ($this->category) {
            $segments[] = Cache::tags(Category::class)
                ->rememberForever('category:' . $this->region->id, function () {
                    return $this->category->getPath();
                });
        }

        return implode('/', $segments);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'adverts_path';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @return AdvertPath|\Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        $chunks = explode('/', $value);

        $region = null;

        do {
            $slug = reset($chunks);

            $next = Region::where('slug', $slug)
                ->where('parent_id', $region ? $region->id : null)
                ->first();

            if ($slug && $next) {
                $region = $next;
                array_shift($chunks);
            }
        } while (!empty($slug) && !empty($next));

        $category = null;

        do {
            $slug = reset($chunks);

            $next = Category::where('slug', $slug)
                ->where('parent_id', $category ? $category->id : null)
                ->first();

            if ($slug && $next) {
                $category = $next;
                array_shift($chunks);
            }

        } while (!empty($slug) && !empty($next));

        if (!empty($chunks)) {
            abort(404);
        }

        return $this
            ->withRegion($region)
            ->withCategory($category);
    }
}