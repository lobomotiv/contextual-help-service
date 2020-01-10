<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\JwtAuthFailureListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;
use Middleware\Auth\Jwt\Events\JwtAuthFailure;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $listen = [
        JwtAuthFailure::class => [
            JwtAuthFailureListener::class,
        ],
    ];
}
