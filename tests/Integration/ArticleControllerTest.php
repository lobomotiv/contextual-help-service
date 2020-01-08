<?php

namespace Test\Integration;

use App\Clients\ZendeskClient;
use Illuminate\Http\Response;
use Test\Helpers\JwtAuth;

class ArticleControllerTest extends TestCase
{

    use JwtAuth;

    private $zendeskClientMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->zendeskClientMock = $this->createMock(ZendeskClient::class);
        $this->app->instance(ZendeskClient::class, $this->zendeskClientMock);
    }

    /**
     * @test
     */
    public function index_calledWithoutJwtAuth_returns401(): void
    {
        $this->get('article/123');
        $this->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function index_calledWithInvalidJwtAuth_returns401(): void
    {
        $this->get('article/123', $this->generateInvalidJwtHeader());
        $this->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function index_calledWithRequiredGetParams_returns200(): void
    {
        $this->zendeskClientMock
            ->expects($this->once())
            ->method('getArticleById')
            ->willReturn([
                'body' => '<h1>Dummy Response</h1>',
                'html_url' => 'http://example.com/',
                'title' => 'Dummy Response',
            ]);
        $this->get('article/123', $this->generateValidJwtHeader());

        $this->assertResponseStatus(Response::HTTP_OK);
    }
}
