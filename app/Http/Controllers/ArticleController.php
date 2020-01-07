<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function index(int $id): JsonResponse
    {
        return new JsonResponse([
            'body' => '<h1>Dummy Response</h1>',
            'url' => 'http://example.com',
            'title' => 'Dummy Response',
        ]);
    }
}
