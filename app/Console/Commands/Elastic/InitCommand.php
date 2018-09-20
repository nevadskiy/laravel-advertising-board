<?php

namespace App\Console\Commands\Elastic;

use App\Entity\Adverts\Advert;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Console\Command;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init elastic engine';

    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new command instance.
     *
     * @param Client $client
     * @return void
     */
    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->removeCurrentIndic();
        $this->createIndic();
        $this->seed();
    }

    protected function seed()
    {
        foreach (Advert::active()->orderBy('id')->cursor() as $advert) {
            $this->client->index([
                'index' => 'app',
                'type' => 'adverts',
                'id' => $advert->id,
                'body' => [
                    'id' => $advert->id,
                    'published_at' => $advert->published_at ? $advert->published_at->format(DATE_ATOM) : null,
                    'title' => $advert->title,
                    'content' => $advert->content,
                    'price' => $advert->price,
                ],
            ]);
        }
    }

    protected function removeCurrentIndic(): void
    {
        try {
            $this->client->indices()->delete([
                'index' => 'app',
            ]);
        } catch (Missing404Exception $e) {}
    }

    protected function createIndic(): void
    {
        $this->client->indices()->create([
            'index' => 'app',

            'body' => [
                // Mapping table entities
                // Can be skipped so elastic makes it for us
                // But for master-control search behaviour should be set
                'mappings' => [
                    'adverts' => [
                        // Id data should returned ?? TODO: make it clear!
                        '_source' => [
                            'enabled' => true,
                        ],
                        // Properties of doc 'adverts'
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'published_at' => [
                                'type' => 'date',
                            ],
                            'title' => [
                                'type' => 'text',
                            ],
                            'content' => [
                                'type' => 'text'
                            ],
                            'price' => [
                                'type' => 'integer'
                            ],
                            'status' => [
                                // Do not use field for full-text searching instead of text
                                // Search if equals (not %like%)
                                'type' => 'keyword'
                            ],
                            'categories' => [
                                // Array can be also assigned (so integer means type of array items)
                                'type' => 'integer'
                            ],
                            'regions' => [
                                // Array can be also assigned (so integer means type of array items)
                                'type' => 'integer'
                            ],
                        ]
                    ]
                ]
            ],
        ]);
    }
}
