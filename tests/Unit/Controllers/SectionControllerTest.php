<?php

namespace Test\Unit\Controller;

use App\Clients\ZendeskClient;
use App\Exceptions\NotFoundArticle;
use App\Http\Controllers\SectionController;
use App\Services\ZendeskArticleIdMapper;
use HTMLPurifier;
use Illuminate\Http\Response;
use Symfony\Component\DomCrawler\Crawler;
use Test\TestCase;

class SectionControllerTest extends TestCase
{
    private const NOT_EXISTING_ARTICLE_ID = 769;
    private const ARTICLE_ID = 123;
    private const STRING_ID = 'email_campaigns';

    private $zendeskClientMock;
    private $controller;
    private $mapperMock;

    protected function setUp(): void
    {
        parent::setUp();

        $purifier = $this->app->get(HTMLPurifier::class);
        $this->zendeskClientMock = $this->createMock(ZendeskClient::class);
        $crawler = $this->app->get(Crawler::class);
        $this->mapperMock = $this->createMock(ZendeskArticleIdMapper::class);

        $this->controller = new SectionController($purifier, $this->zendeskClientMock, $crawler, $this->mapperMock);
    }

    /**
     * @test
     */
    public function index_calledWithNotExistingArticleId_returns404(): void
    {
        $this->zendeskClientMock
            ->expects($this->once())
            ->method('getArticleById')
            ->with(self::NOT_EXISTING_ARTICLE_ID)
            ->willThrowException(new NotFoundArticle());

        $response = $this->controller->index(self::NOT_EXISTING_ARTICLE_ID, 'section_test');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function index_calledWithExistingArticleIdAndNotExistingSectionName_returns404(): void
    {
        $article = [
            'body' => '',
            'html_url' => 'http://example.com/',
            'title' => 'Dummy Response',
        ];
        $this->zendeskClientMock
            ->expects($this->once())
            ->method('getArticleById')
            ->with(self::ARTICLE_ID)
            ->willReturn($article);

        $response = $this->controller->index(self::ARTICLE_ID, 'not_exists_section');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function index_calledWithExistingArticleIdAndSectionName_returnsArticleBody(): void
    {
        $article = [
            'body' => '<span><h1>Body</h1><div id="section_test"><script>alert("hello")</script><h1>Test Section Content</h1></div></span>',
            'html_url' => 'http://example.com/',
            'title' => 'Dummy Response',
        ];

        $this->zendeskClientMock
            ->expects($this->once())
            ->method('getArticleById')
            ->with(self::ARTICLE_ID)
            ->willReturn($article);

        $response = $this->controller->index(self::ARTICLE_ID, 'section_test');

        $expectedResponse = [
            'body' => '<div id="section_test"><h1>Test Section Content</h1></div>'
        ];

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getData(true));
    }


    /**
     * @test
     */
    public function index_calledWithStringIdAndSectionName_callsZendeskWithArticleIdFromMap(): void
    {
        $article = [
            'body' => '<span><h1>Body</h1><div id="section_test"><script>alert("hello")</script><h1>Test Section Content</h1></div></span>',
            'html_url' => 'http://example.com/',
            'title' => 'Dummy Response',
        ];

        $this->mapperMock
            ->expects($this->once())
            ->method('getZendeskId')
            ->with(self::STRING_ID)
            ->willReturn(self::ARTICLE_ID);

        $this->zendeskClientMock
            ->expects($this->once())
            ->method('getArticleById')
            ->with(self::ARTICLE_ID)
            ->willReturn($article);

        $response = $this->controller->index(self::STRING_ID, 'section_test');

        $expectedResponse = [
            'body' => '<div id="section_test"><h1>Test Section Content</h1></div>'
        ];

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getData(true));
    }
}
