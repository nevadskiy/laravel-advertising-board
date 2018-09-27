<?php

namespace App\Console\Commands\Adverts;

use App\Entity\Advert\Advert;
use App\Services\Adverts\AdvertService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireCommand extends Command
{
    protected $signature = 'advert:expire';

    protected $description = 'Remove expired adverts';

    protected $service;

    public function __construct(AdvertService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle()
    {
        foreach ($this->getActiveExpiredAdverts() as $advert) {
            try {
                $this->service->expire($advert);
            } catch (\DomainException $e) {
                $this->error($e->getMessage());
            }
        }
    }

    protected function getActiveExpiredAdverts()
    {
        return Advert::active()->where('expires_at', '<', Carbon::now())->cursor();
    }
}
