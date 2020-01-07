<?php

namespace Test\Integration;

use Illuminate\Http\Response;
use Test\Helpers\JwtAuth;

class ArticleControllerTest extends TestCase
{

    use JwtAuth;

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
    public function index_calledWithRequiredPostParams_returns200(): void
    {
        $this->get('article/123', $this->generateValidJwtHeader());
        $this->assertResponseStatus(Response::HTTP_OK);
    }
}
