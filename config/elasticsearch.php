<?php

return [
    'connection' => [
        'host' => env('ELASTICSEARCH_HOST', 'elasticsearch'),
        'port' => env('ELASTICSEARCH_PORT', 9200),

        'retries' => 1
    ]
];
