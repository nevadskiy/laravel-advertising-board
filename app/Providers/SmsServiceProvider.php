<?php

namespace App\Providers;

use App\Services\Sms\ArraySender;
use App\Services\Sms\NexmoSmsSender;
use App\Services\Sms\SmsSender;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SmsSender::class, function ($app) {
            $config = $app->make('config')->get('sms');

            switch ($config['driver']) {
                case 'nexmo':
                    return new NexmoSmsSender(
                        $config['drivers']['nexmo']['app_key'],
                        $config['drivers']['nexmo']['app_secret']
                    );
                case 'array':
                    return new ArraySender();
                default:
                    throw new \InvalidArgumentException('Undefined SMS driver ' . $config['driver']);
            }
        });
    }
}
