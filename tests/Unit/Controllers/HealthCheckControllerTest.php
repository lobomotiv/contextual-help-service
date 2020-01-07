<?php

namespace Test\Unit\Controller;

use Psr\Log\LogLevel;
use Test\TestCase;

class HealthCheckControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index_EverythingIsFine_ReturnsSuccessTrue(): void
    {
        $this->get('/healthcheck');

        $this->assertResponseStatus(200);
        $this->seeJson(['success' => true]);
    }

    /**
     * @test
     */
    public function index_EverythingIsFine_LogsHealthCheckWasCalled(): void
    {
        $mockLogger = $this->mockLogger();
        $this->get('/healthcheck');

        $mockLogger->assertLoggedMessage(LogLevel::INFO, 'healthcheck was called');
    }
}
