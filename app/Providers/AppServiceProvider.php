<?php

namespace App\Providers;

use App\Services\Banner\CostCalculator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CostCalculator::class, function (Application $app) {
            return new CostCalculator($app->make('config')->get('banner')['price']);
        });

        Passport::ignoreMigrations();
    }
}
