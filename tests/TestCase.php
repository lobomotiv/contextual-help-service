<?php

namespace Test;

use Laravel\Lumen\Testing\TestCase as LumenTestcase;
use Psr\Log\LoggerInterface;
use TiMacDonald\Log\LogFake;

abstract class TestCase extends LumenTestcase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function mockLogger(): LogFake
    {
        $mockLogger = new LogFake();
        $this->app->instance(LoggerInterface::class, $mockLogger);

        return $mockLogger;
    }
}
