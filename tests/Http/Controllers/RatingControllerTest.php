<?php

namespace Test\Http\Controllers;

use App\Http\Controllers\RatingController;
use Test\TestCase;

class RatingControllerTest extends TestCase
{

    /**
     * @test
     */
    public function classExists()
    {
        $controller = new RatingController();

        $this->assertInstanceOf(RatingController::class, $controller);
    }
}
