<?php

namespace Test\Unit\Controller;

use App\Http\Controllers\ArticleController;
use Test\TestCase;

class ArticleControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index_calledWithId_returnsDummyArticle()
    {
        $purifier = app(\HTMLPurifier::class);
        $controller = new ArticleController($purifier);
        $response = $controller->index(123);

        $expectedResponse = [
            'body' => '<h1>Dummy Response</h1>',
            'url' => 'http://example.com/123',
            'title' => 'Dummy Response',
        ];

        $this->assertEquals($expectedResponse, $response->getData(true));
    }
}
