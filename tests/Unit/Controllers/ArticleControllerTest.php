<?php

namespace Test\Unit\Controller;

use App\Clients\ZendeskClient;
use App\Exceptions\NotFoundArticle;
use App\Http\Controllers\ArticleController;
use HTMLPurifier;
use Test\TestCase;

class ArticleControllerTest extends TestCase
{
    private const NOT_EXISTING_ARTICLE_ID = 967;
    private const ARTICLE_ID = 123;
    private const HTTP_NOT_FOUND = 404;
    private const HTTP_OK = 200;

    /**
     * @test
     */
    public function index_calledWithNotExistingId_returns404(): void
    {
        $purifier = $this->app->get(HTMLPurifier::class);

        $zendeskClientMock = $this->createMock(ZendeskClient::class);
        $zendeskClientMock
            ->expects($this->once())
            ->method('getArticleById')
            ->with(self::NOT_EXISTING_ARTICLE_ID)
            ->willThrowException(new NotFoundArticle());

        $controller = new ArticleController($purifier, $zendeskClientMock);
        $response = $controller->index(self::NOT_EXISTING_ARTICLE_ID);

        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function index_calledWithExistingId_returnsArticleData(): void
    {
        $article = [
            'body' => '<script>alert("hello")</script><h1>Dummy Response</h1>',
            'html_url' => 'http://example.com/',
            'title' => 'Dummy Response',
        ];
        $expectedResponseBody = [
            'body' => '<h1>Dummy Response</h1>',
            'url' => $article['html_url'],
            'title' => $article['title'],
        ];

        $purifier = $this->app->get(HTMLPurifier::class);
        $zendeskClientMock = $this->createMock(ZendeskClient::class);
        $zendeskClientMock
            ->expects($this->once())
            ->method('getArticleById')
            ->with(self::ARTICLE_ID)
            ->willReturn($article);
        $controller = new ArticleController($purifier, $zendeskClientMock);

        $response = $controller->index(self::ARTICLE_ID);

        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponseBody, $response->getData(true));
    }

}
