<?php

namespace Test\Unit\Controller;

use App\Clients\ZendeskClient;
use App\Exceptions\NotFoundArticle;
use App\Http\Controllers\SectionController;
use HTMLPurifier;
use Symfony\Component\DomCrawler\Crawler;
use Test\TestCase;

class SectionControllerTest extends TestCase
{
    private const NOT_EXISTING_ARTICLE_ID = 769;
    private const ARTICLE_ID = 123;

    private $zendeskClientMock;
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $purifier = $this->app->get(HTMLPurifier::class);
        $this->zendeskClientMock = $this->createMock(ZendeskClient::class);
        $crawler = $this->app->get(Crawler::class);

        $this->controller = new SectionController($purifier, $this->zendeskClientMock, $crawler);
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

        $this->assertEquals(404, $response->getStatusCode());
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

        $this->assertEquals(404, $response->getStatusCode());
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
            'body' => '<div><h1>Test Section Content</h1></div>'
        ];

        $this->assertEquals($expectedResponse, $response->getData(true));
    }
}
