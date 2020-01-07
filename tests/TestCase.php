<?php

namespace Test;

use Laravel\Lumen\Testing\TestCase as LumenTestcase;

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
}
