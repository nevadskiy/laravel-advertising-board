<?php

namespace App\Services\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Requests\Adverts\SearchRequest;
use Elasticsearch\Client;
use Illuminate\Contracts\Pagination\Paginator;

class AdvertSearchService
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(
        ?Category $category,
        ?Region $region,
        SearchRequest $request,
        int $perPage,
        int $currentPage
    ): Paginator
    {
        $response = $this->client->search([
            'index' => 'app',
            'type' => 'adverts',
            'body' => [
                // Array to return
                '_source' => ['id'],
                // Paginating
                'from' => ($currentPage - 1) * $perPage,
                'size' => $perPage,

                // Querying
                'query' => [
                    'bool' => [
                        // equals string
                        ['term' => ['status' => Advert::STATUS_ACTIVE]],
                        ['term' => ['categories' => $category->id]],
                        ['term' => ['regions' => $region->id]],

                    ]
                ],

                // Sort found result array (weight of item)
                // 'sort' => [
                // ['published_at' => ['order' => 'desc']]
                // ]
            ]
        ]);

        // Pluck all found result ids
        $ids = array_column($response['hits']['hits'], '_id');

        $items = Advert::active()
            ->with(['category', 'region'])
            ->whereIn('id', $ids)
            ->orderBy('FIELD(id,' . implode(',', $ids) .')')
            ->get();
    }
}
