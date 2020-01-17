<?php

namespace Test\Integration;

use App\Services\Rating;
use Illuminate\Http\Response;
use Test\Helpers\JwtAuth;

class RatingControllerTest extends TestCase
{

    use JwtAuth;

    private $ratingServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ratingServiceMock = $this->createMock(Rating::class);
        $this->app->instance(Rating::class, $this->ratingServiceMock);
    }

    /**
     * @test
     */
    public function delete_calledWithoutJwtAuth_returns401(): void
    {
        $this->delete('article/123/rate');
        $this->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function delete_calledWithInvalidJwtAuth_returns401(): void
    {
        $this->delete('article/123/rate', [], $this->generateInvalidJwtHeader());
        $this->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function delete_calledWithRequiredParams_returns200(): void
    {
        $this->ratingServiceMock
            ->expects($this->once())
            ->method('deleteVote');

        $this->delete('article/123/rate', [], $this->generateValidJwtHeader());

        $this->assertResponseStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function get_calledWithoutJwtAuth_returns401(): void
    {
        $this->get('article/123/rate');
        $this->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function get_calledWithInvalidJwtAuth_returns401(): void
    {
        $this->get('article/123/rate', [], $this->generateInvalidJwtHeader());
        $this->assertResponseStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function get_calledWithRequiredParams_returns200(): void
    {
        $this->ratingServiceMock
            ->expects($this->once())
            ->method('getVote');

        $this->get('article/123/rate', $this->generateValidJwtHeader());

        $this->assertResponseStatus(Response::HTTP_OK);
    }
}
