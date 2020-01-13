<?php

namespace Test\Unit\Controller;

use Illuminate\Http\Response;
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

        $this->assertResponseStatus(Response::HTTP_OK);
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

    /**
     * @test
     */
    public function index_RedisIsUnavailable_LogsRedisConnectionFailed(): void
    {
        $mockLogger = $this->mockLogger();
        config()->set('database.redis.default.password', 'wrong_password');
        $this->get('/healthcheck');

        $mockLogger->assertLoggedMessage(LogLevel::ERROR, 'Redis is unavailable');
        $this->assertResponseStatus(Response::HTTP_OK);
        $this->seeJson(['success' => false, 'error' => 'Redis is unavailable']);
    }
}
