<?php

return [
    'redis' => [
        'client' => 'predis',
        'default' => [
            'host' => env('REDIS_HOST'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT'),
            'database' => env('REDIS_DB'),
        ],
    ],
];
