<?php

namespace App\Console\Commands\Elastic;

use App\Entity\Adverts\Advert;
use App\Services\Search\AdvertIndexer;
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
     * @var AdvertIndexer
     */
    private $advertIndexer;

    /**
     * Create a new command instance.
     *
     * @param Client $client
     * @param AdvertIndexer $advertIndexer
     */
    public function __construct(Client $client, AdvertIndexer $advertIndexer)
    {
        parent::__construct();
        $this->client = $client;
        $this->advertIndexer = $advertIndexer;
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
                            // Advert attributes mapping
                            'values' => [
                                // Means that type of field is nested array (not simple array like [1,2,3])
                                'type' => 'nested',
                                // Nested properties mapping (the same, like with adverts on one level up)
                                'properties' => [
                                    'attribute' => [
                                        'type' => 'integer',
                                    ],
                                    'value_number' => [
                                        'type' => 'integer',
                                    ],
                                    'value_string' => [
                                        'type' => 'keyword',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                // Use once during import (for change settings, data should be reimported)
                'settings' => [
                    // Analyzer settings
                    'analysis' => [
                        // Filters for char
                        'char_filter' => [
                            // Should be replaced
                            'replace' => [
                                'type' => 'mapping',
                                // '&' symbol will be replace to 'spaceANDspace'
                                'mappings' => ['&=> and ']
                            ],
                        ],
                        // Filters
                        'filter' => [
                            // Applies inside one single word
                            // (if case changed, if digit exists, etc)
                            'word_delimiter' => [
                                'type' => 'word_delimiter',
                                'split_on_numerics' => false,
                                'split_on_case_change' => true,
                                'generate_word_parts' => true,
                                'generate_number_parts' => true,
                                'catenate_all' => true,
                                'preserve_original' => true,
                                'catenate_numbers' => true,
                            ],
                            // Partials search (extends full substring search with search by partials)
                            'trigrams' => [
                                'type' => 'ngram',
                                // Search from 4 chars (split words to parts from 4 to 6 chars)
                                'min_gram' => 4,
                                'max_gram' => 6,
                            ],
                        ],

                        // Analyzators
                        'analyzer' => [
                            // Default analyzator (use for text field type)
                            // If should be custom analyzator used, need to give it name
                            // and use it for properties where it requires
                            'default' => [
                                'type' => 'custom',
                                'char_filter' => [
                                    'html_strip',
                                    'replace' // name of filter for char (defined above inside settings.chat_filter key)
                                ],
                                'tokenizer' => 'whitespace',
                                'filter' => [
                                    'lowercase',
                                    'word_delimiter', // name of filter (defined above inside settings.filter key)
                                    'trigrams'
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
