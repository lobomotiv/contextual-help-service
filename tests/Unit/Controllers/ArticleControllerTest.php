<?php

namespace Test\Unit\Controller;

use App\Clients\ZendeskClient;
use App\Exceptions\NotFoundArticle;
use App\Http\Controllers\ArticleController;
use App\Services\ZendeskMapper;
use HTMLPurifier;
use Illuminate\Http\Response;
use Test\TestCase;

class ArticleControllerTest extends TestCase
{
    private const NOT_EXISTING_ARTICLE_ID = 967;
    private const ARTICLE_ID = 123;
    private const STRING_ID = 'email_campaigns';

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

        $mapper = $this->createMock(ZendeskMapper::class);

        $controller = new ArticleController($purifier, $zendeskClientMock, $mapper);
        $response = $controller->index(self::NOT_EXISTING_ARTICLE_ID);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
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

        $mapper = $this->createMock(ZendeskMapper::class);

        $controller = new ArticleController($purifier, $zendeskClientMock, $mapper);

        $response = $controller->index(self::ARTICLE_ID);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponseBody, $response->getData(true));
    }

    /**
     * @test
     */
    public function index_calledWithStringId_callsZendeskWithArticleIdFromMap(): void
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

        $mapper = $this->createMock(ZendeskMapper::class);
        $mapper
            ->expects($this->once())
            ->method('getZendeskArticleId')
            ->with(self::STRING_ID)
            ->willReturn(self::ARTICLE_ID);

        $zendeskClientMock = $this->createMock(ZendeskClient::class);
        $zendeskClientMock
            ->expects($this->once())
            ->method('getArticleById')
            ->with(self::ARTICLE_ID)
            ->willReturn($article);

        $controller = new ArticleController($purifier, $zendeskClientMock, $mapper);
        $response = $controller->index(self::STRING_ID);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponseBody, $response->getData(true));
    }

}
