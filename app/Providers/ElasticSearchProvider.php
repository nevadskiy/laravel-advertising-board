<?php

namespace App\Providers;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ElasticSearchProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function (Application $app) {

            $config = $app->make('config')->get('elasticsearch');

            return $client = ClientBuilder::create()
                ->setHosts($this->getHostsAttribute($config))
                ->setRetries($config['connection']['retries'])
                ->setLogger(app('log'))
                ->build();
        });
    }

    /**
     * @param array $config
     * @return array
     */
    private function getHostsAttribute(array $config)
    {
        return [$config['connection']['host'] . ':' . $config['connection']['port']];
    }
}
