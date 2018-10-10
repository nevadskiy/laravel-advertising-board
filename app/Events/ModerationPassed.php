<?php

namespace App\Events;

use App\Entity\Advert\Advert;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ModerationPassed implements ShouldQueue
{
    use Dispatchable, SerializesModels;

    public $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }
}
