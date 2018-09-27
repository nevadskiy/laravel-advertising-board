<?php

namespace App\Console\Commands\Elastic;

use App\Entity\Advert\Advert;
use App\Entity\Banner\Banner;
use App\Services\Search\AdvertIndexer;
use App\Services\Search\BannerIndexer;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class ReIndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear and run elastic indexer from DB data';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var AdvertIndexer
     */
    private $advertIndexer;

    /**
     * @var BannerIndexer
     */
    private $bannerIndexer;

    /**
     * Create a new command instance.
     *
     * @param Client $client
     * @param AdvertIndexer $advertIndexer
     */
    public function __construct(Client $client, AdvertIndexer $advertIndexer, BannerIndexer $bannerIndexer)
    {
        parent::__construct();
        $this->client = $client;
        $this->advertIndexer = $advertIndexer;
        $this->bannerIndexer = $bannerIndexer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('--- Clear current index ---');

        $this->advertIndexer->clear();

        $this->info('--- Start indexing data ---');

        foreach (Advert::active()->orderBy('id')->cursor() as $advert) {
            $this->advertIndexer->index($advert);
        }

        $this->bannerIndexer->clear();

        foreach (Banner::active()->orderBy('id')->cursor() as $banner) {
            $this->banners->index($banner);
        }

        $this->info('--- Finish reindexing ---');
    }
}
