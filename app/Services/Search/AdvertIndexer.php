<?php

namespace App\Services\Search;

use App\Entity\Advert\Advert;
use App\Entity\Advert\Value;
use Elasticsearch\Client;

class AdvertIndexer
{
    /**
     * @var Client
     */
    private $client;

    /**
     * AdvertIndexer constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     *
     */
    public function clear(): void
    {
        $this->client->deleteByQuery([
            'index' => 'adverts',
            'type' => 'advert',
            'body' => [
                'query' => [
                    // where match_all = {}
                    // (new empty stdClass with json_encode will transform into "{}")
                    'match_all' => new \stdClass()
                ]
            ]
        ]);
    }

    /**
     * @param Advert $advert
     */
    public function index(Advert $advert): void
    {
        // Add regions
        $regions = [];
        if ($region = $advert->region) {
            do {
                $regions[] = $region->id;
            } while ($region = $region->parent);
        }

        $this->client->index([
            'index' => 'adverts',
            'type' => 'advert',
            'id' => $advert->id,
            'body' => [
                'id' => $advert->id,
                'published_at' => $advert->published_at ? $advert->published_at->format(DATE_ATOM) : null,
                'title' => $advert->title,
                'content' => $advert->content,
                'price' => $advert->price,
                'status' => $advert->status,
                'categories' => array_merge(
                    [$advert->category->id],
                    $advert->category->ancestors()->pluck('id')->toArray()
                ),
                'regions' => $regions,
                // Get nested array of attributes
                'values' => array_map(function (Value $value) {
                    return [
                        'attribute' => $value->attribute_id,
                        'value_string' => (string)$value->value,
                        'value_number' => (int)$value->value,
                    ];
                }, $advert->values()->getModels())
            ],
        ]);
    }

    /**
     * @param Advert $advert
     */
    public function remove(Advert $advert): void
    {
        $this->client->delete([
            'index' => 'adverts',
            'type' => 'advert',
            'id' => $advert->id
        ]);
    }
}
