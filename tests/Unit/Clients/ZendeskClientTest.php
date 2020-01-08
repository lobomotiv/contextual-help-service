<?php

namespace Test\Unit\Clients;

use App\Clients\ZendeskClient;
use App\Exceptions\NotFoundArticle;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Test\TestCase;

class ZendeskClientTest extends TestCase
{
    private const NOT_EXISTING_ARTICLE_ID = 967;
    private const ARTICLE_ID = 123;

    /**
     * @test
     */
    public function getArticle_givenNotExistingId_throwsException(): void
    {
        $mockResponses = new MockHandler([
            new Response(404, [], json_encode('{"error":"RecordNotFound","description":"Not found"}')),
        ]);
        $guzzleClient = $this->createClientWithMockResponses($mockResponses);

        $client = new ZendeskClient($guzzleClient);

        $this->expectException(NotFoundArticle::class);
        $this->expectExceptionMessage('Article not found with given id: ' . self::NOT_EXISTING_ARTICLE_ID);

        $client->getArticleById(self::NOT_EXISTING_ARTICLE_ID);
    }

    /**
     * @test
     */
    public function getArticle_givenExistingId_returnsArticleData(): void
    {
        $expectedResult = [
            'article' => [
                'html_url' => 'https://help.emarsys.com/hc/en-us/articles/115004581365-Overview-Transactional-opt-in',
                'title' => 'Overview:: Transactional opt-in',
                'body' => '<h1>Test body</h1>'
            ]
        ];

        $mockResponses = new MockHandler([
            new Response(200, [], json_encode($expectedResult)),
        ]);
        $guzzleClient = $this->createClientWithMockResponses($mockResponses);

        $client = new ZendeskClient($guzzleClient);
        $result = $client->getArticleById(self::ARTICLE_ID);

        $this->assertEquals($expectedResult, $result);
    }

    private function createClientWithMockResponses(MockHandler $mockResponses): Client
    {
        return new Client(['handler' => HandlerStack::create($mockResponses)]);
    }
}
