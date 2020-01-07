<?php

namespace Test\Controller;

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
}
