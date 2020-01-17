<?php

namespace Test\Http\Controllers;

use App\Exceptions\RatingNotFound;
use App\Http\Controllers\RatingController;
use App\Services\Rating;
use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\MockObject;
use Test\TestCase;

class RatingControllerTest extends TestCase
{
    private const NON_EXISTING_ARTICLE_ID = 123;
    private const ARTICLE_ID = 346;
    private const CUSTOMER_ID = 818;
    private const ADMIN_ID = 467;

    /**
     * @var Request
     */
    private $request;
    /**
     * @var Rating|MockObject
     */
    private $ratingServiceMock;
    /**
     * @var RatingController
     */
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new Request([
            'tokenPayload' => [
                'customerId' => self::CUSTOMER_ID,
                'adminId' => self::ADMIN_ID
            ]
        ]);
        $this->ratingServiceMock = $this->createMock(Rating::class);
        $this->controller = new RatingController($this->ratingServiceMock);
    }

    /**
     * @test
     */
    public function delete_nonExistingArticleId_returns404(): void
    {
        $this->ratingServiceMock
            ->expects($this->once())
            ->method('deleteVote')
            ->with(self::NON_EXISTING_ARTICLE_ID, self::CUSTOMER_ID, self::ADMIN_ID)
            ->willThrowException(new RatingNotFound());

        $response = $this->controller->delete($this->request, self::NON_EXISTING_ARTICLE_ID);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function delete_existingArticleId_returns200(): void
    {
        $this->ratingServiceMock
            ->expects($this->once())
            ->method('deleteVote')
            ->with(self::ARTICLE_ID, self::CUSTOMER_ID, self::ADMIN_ID);

        $response = $this->controller->delete($this->request, self::ARTICLE_ID);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function get_nonExistingArticleId_returns404(): void
    {
        $this->ratingServiceMock
            ->expects($this->once())
            ->method('getVote')
            ->with(self::NON_EXISTING_ARTICLE_ID, self::CUSTOMER_ID, self::ADMIN_ID)
            ->willThrowException(new RatingNotFound());

        $response = $this->controller->get($this->request, self::NON_EXISTING_ARTICLE_ID);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function get_existingArticleId_returns200WithRating(): void
    {
        $this->ratingServiceMock
            ->expects($this->once())
            ->method('getVote')
            ->with(self::ARTICLE_ID, self::CUSTOMER_ID, self::ADMIN_ID)
            ->willReturn('up');

        $response = $this->controller->get($this->request, self::ARTICLE_ID);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"vote":"up"}', $response->getContent());
    }
}
