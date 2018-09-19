<?php

namespace App\Providers;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    protected $classes = [
        Region::class,
        Category::class
    ];
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach ($this->classes as $class) {
            $this->bootFlusher($class);
        }
    }

    protected function bootFlusher($class): void
    {
        /** @var Model $class */
        $flush = function () use ($class) {
            Cache::tags($class)->flush();
        };

        $class::created($flush);
        $class::saved($flush);
        $class::updated($flush);
        $class::deleted($flush);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
