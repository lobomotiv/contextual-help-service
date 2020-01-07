<?php

namespace Test\Controller;

use Test\TestCase;

class ArticleControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index_calledWithId_returnsDummyArticle()
    {
        $this->get('/article/123');

        $this->assertResponseStatus(200);
        $this->seeJson([
            'body' => '<h1>Dummy Response</h1>',
            'url' => 'http://example.com',
            'title' => 'Dummy Response',
        ]);
    }
}
