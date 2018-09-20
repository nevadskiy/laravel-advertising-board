<?php

namespace App\Console\Commands\Elastic;

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
    }

    public function removeCurrentIndic(): void
    {
        try {
            $this->client->indices()->delete([
                'index' => 'app',
            ]);
        } catch (Missing404Exception $e) {}
    }

    public function createIndic(): void
    {
        $this->client->indices()->create([
            'index' => 'app',
            'body' => [
                'mappings' => [
                    'adverts' => [
                        '_source' => [
                            'enabled' => true,
                        ],
                        'properties' => [
                            'id' => [
                                'type' => 'integer'
                            ]
                        ]
                    ]
                ]
            ],
        ]);
    }
}
