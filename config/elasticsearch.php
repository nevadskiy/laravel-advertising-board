<?php

return [
    'connection' => [
        'host' => env('ELASTICSEARCH_HOST', 'elasticsearch'),
        'post' => env('ELASTICSEARCH_PORT', 9200),

        'retries' => 1
    ]
];
