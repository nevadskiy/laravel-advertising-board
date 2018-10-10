<?php

namespace App\Providers;

use App\Events\ModerationPassed;
use App\Listeners\AdvertChangedListener;
use App\Listeners\ModerationPassedListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ModerationPassed::class => [
            AdvertChangedListener::class,
            ModerationPassedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
