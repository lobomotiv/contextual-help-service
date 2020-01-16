<?php

namespace Test\Integration;

use App\Clients\ZendeskClient;
use App\Services\ZendeskMapper;
use Illuminate\Http\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Test\Helpers\JwtAuth;

class SectionControllerTest extends TestCase
{

    use JwtAuth;

    /**
     * @var ZendeskClient|MockObject
     */
    private $zendeskClientMock;

    /**
     * @var ZendeskClient|MockObject
     */
    private $zendeskMapperMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->zendeskClientMock = $this->createMock(ZendeskClient::class);
        $this->zendeskMapperMock = $this->createMock(ZendeskMapper::class);

        $this->app->instance(ZendeskClient::class, $this->zendeskClientMock);
        $this->app->instance(ZendeskMapper::class, $this->zendeskMapperMock);
    }

    /**
     * @test
     */
    public function index_calledWithoutJwtAuth_returns401(): void
    {
        $this->get('article/123/section/test-section-name');
        $this->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function index_calledWithInvalidJwtAuth_returns401(): void
    {
        $this->get('article/123/section/test-section-name', $this->generateInvalidJwtHeader());
        $this->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function index_calledWithRequiredGetParams_returns200(): void
    {
        $articleId = 123;
        $sectionId = 'test-section-name';

        $this->zendeskClientMock
            ->expects($this->once())
            ->method('getArticleById')
            ->willReturn([
                'body' => '<div id="test-section-name"><h1>Dummy Response</h1></div>',
                'html_url' => 'http://example.com/',
                'title' => 'Dummy Response',
            ]);

        $this->zendeskMapperMock
            ->expects($this->once())
            ->method('getZendeskSectionId')
            ->with($articleId, $sectionId)
            ->willReturn($sectionId);

        $this->get('article/'. $articleId .'/section/' . $sectionId, $this->generateValidJwtHeader());
        $this->assertResponseStatus(Response::HTTP_OK);
    }
}
