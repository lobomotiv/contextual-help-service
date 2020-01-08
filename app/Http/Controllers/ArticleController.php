<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * @var \HTMLPurifier
     */
    private $purifier;

    public function __construct(\HTMLPurifier $purifier)
    {
        $this->purifier = $purifier;
    }

    public function index(int $id): JsonResponse
    {
        $responseBody = '<script>alert("test")</script><h1>Dummy Response</h1>';

        return new JsonResponse([
            'body' => $this->purifier->purify($responseBody),
            'url' => 'http://example.com/' . $id,
            'title' => 'Dummy Response',
        ]);
    }
}
