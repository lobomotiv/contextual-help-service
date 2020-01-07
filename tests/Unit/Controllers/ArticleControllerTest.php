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
        $controller = new ArticleController();
        $response = $controller->index(123);

        $expectedResponse = [
            'body' => '<h1>Dummy Response</h1>',
            'url' => 'http://example.com',
            'title' => 'Dummy Response',
        ];

        $this->assertEquals($expectedResponse, $response->getData(true));
    }
}
