<?php

namespace App\Listeners;

use App\Events\ModerationPassed;
use App\Notifications\Advert\ModerationPassedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModerationPassedListener
{
    public function handle(ModerationPassed $event): void
    {
        $advert = $event->advert;
        $advert->user->notify(new ModerationPassedNotification($advert));
    }
}
