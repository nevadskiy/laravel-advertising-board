<?php

namespace App\Listeners;

use App\Services\Search\AdvertIndexer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdvertChangedListener
{
    private $indexer;

    public function __construct(AdvertIndexer $indexer)
    {
        $this->indexer = $indexer;
    }

    public function handle($event): void
    {
        $this->indexer->index($event->advert);
    }
}
