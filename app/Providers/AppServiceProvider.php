<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Redis\RedisManager;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            HTMLPurifier::class,
            static function () {
                $config = HTMLPurifier_Config::createDefault();
                $config->set('Attr.EnableID', true);

                return new HTMLPurifier($config);
            }
        );

        $this->app->alias('redis', RedisManager::class);
    }

    public function boot(LoggerInterface $logger)
    {
        $logger->driver()->getLogger()->pushProcessor($this->getLoggerRequestIdDecorator());
    }

    private function getLoggerRequestIdDecorator(): callable
    {
        $requestId = Uuid::uuid4()->toString();

        return static function ($record) use ($requestId) {
            $record['request_id'] = $requestId;

            return $record;
        };
    }
}
