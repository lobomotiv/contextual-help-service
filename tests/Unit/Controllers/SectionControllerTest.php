<?php

namespace Test\Unit\Controller;

use App\Http\Controllers\SectionController;
use Test\TestCase;

class SectionControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index_calledWithId_returnsDummySection()
    {
        $purifier = app(\HTMLPurifier::class);
        $controller = new SectionController($purifier);
        $response = $controller->index(123, 'dummy-section');

        $expectedResponse = [
            'body' => '<h1>Dummy Response 123 dummy-section</h1>'
        ];

        $this->assertEquals($expectedResponse, $response->getData(true));
    }
}
