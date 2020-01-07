<?php

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;

return [
    'default' => env('LOG_CHANNEL', 'json'),
    'channels' => [
        'json' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stdout',
            ],
            'formatter' => JsonFormatter::class,
        ],
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/lumen.log'),
            'level' => 'debug',
        ],
        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],
    ],
];
