<?php

namespace App\Services\Adverts;

use App\Entity\Advert\Advert;
use App\Entity\Advert\Category;
use App\Entity\Region;
use App\Http\Requests\Adverts\SearchRequest;
use Elasticsearch\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class AdvertSearchService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * AdvertSearchService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * TODO: refactor
     * @param Category|null $category
     * @param Region|null $region
     * @param SearchRequest $request
     * @param int $perPage
     * @param int $currentPage
     * @return SearchResult
     */
    public function search(
        ?Category $category,
        ?Region $region,
        SearchRequest $request,
        int $perPage,
        int $currentPage
    ): SearchResult
    {
        $values = array_filter((array)$request->get('attrs'), function ($value) {
            return !empty($value['equals']) || !empty($value['from']) || !empty($value['to']);
        });

        $request = [
            'index' => 'app',
            'type' => 'adverts',
            'body' => [
                // Array to return
                '_source' => ['id'],
                // Paginating
                'from' => ($currentPage - 1) * $perPage,
                'size' => $perPage,
                'sort' => !empty($request['text']) ? [
                    ['published_at' => ['order' => 'desc']],
                ] : [],

                // Aggregations (faceted groups)
                'aggs' => [
                    'group_by_region' => [
                        'terms' => [
                            'field' => 'regions',
                        ],
                    ],
                    'group_by_category' => [
                        'terms' => [
                            'field' => 'categories',
                        ],
                    ]
                ],

                // Querying
                'query' => [
                    // Array filter removes 'false' values
                    'bool' => [
                        // Must means that all params are required for search
                        // If one of them will return no results, so it applies for all fields
                        'must' => array_values(array_filter([
                            // term means: equals string
                            ['term' => ['status' => Advert::STATUS_ACTIVE]],
                            $category ? ['term' => ['categories' => $category->id]] : false,
                            $region ? ['term' => ['regions' => $region->id]] : false,

                            // Multimatch (search inside multiple fields)
                            !empty($request->text) ? ['multi_match' => [
                                'query' => $request->text,
                                // Search inside fields given
                                // Title weight is x3, content is default weight x1
                                'fields' => ['title^3', 'content']
                            ]] : false,

                            // Nested search params (can be multiple definitions)

                            // Trick to pass KEY inside array map:
                            // First array must be target array, second array - keys array of target array
                            // Example:
                            // array_map(function($value, $key) {}, $array, array_keys($values))
                            array_map(function ($value, $id) {
                                return [
                                    'nested' => [
                                        'path' => 'values',
                                        'bool' => [
                                            // Filtering empty attributes and reset indexes with array_values for json_encode
                                            'must' => array_values(array_filter([
                                                ['match' => ['values.attribute' => $id]],
                                                !empty($value['equals']) ? ['match' => ['values.value_string' => $value['equals']]] : false,
                                                !empty($value['from']) ? ['range' => ['values.value_number' => ['gte' => $value['from']]]] :
                                                    !empty($value['to']) ? ['range' => ['values.value_number' => ['lte' => $value['to']]]] : false,
                                            ])),
                                        ],
                                    ]
                                ];
                            }, $values, array_keys($values))
                        ])),
                    ],
                ],
            ],
        ];

         $response = $this->client->search($request);

        // Pluck all found result ids
        $ids = array_column($response['hits']['hits'], '_id');

        $regionsCounts = array_column($response['aggregations']['group_by_region']['buckets'], 'doc_count', 'key');
        $categoriesCounts = array_column($response['aggregations']['group_by_category']['buckets'], 'doc_count', 'key');

        if ($ids) {
            $items = Advert::active()
                ->with(['category', 'region'])
                ->whereIn('id', $ids)
                ->orderBy(\DB::raw('FIELD(id,' . implode(',', $ids) . ')'))
                ->get();
            $paginator = new LengthAwarePaginator($items, $response['hits']['total'], $perPage, $currentPage);
        } else {
            $paginator = new LengthAwarePaginator([], 0, $perPage, $currentPage);
        }

        return new SearchResult($paginator, $regionsCounts, $categoriesCounts);
    }
}
