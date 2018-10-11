<?php

namespace App\Jobs;

use App\Entity\Advert\Advert;
use App\Services\Search\AdvertIndexer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReindexAdvert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }

    // Allows to store serialized object without serialized given service
    // And on job running laravel unserialize queued object and then DI service to work with it
    public function handle(AdvertIndexer $indexer)
    {
        $indexer->index($this->advert);
    }
}
