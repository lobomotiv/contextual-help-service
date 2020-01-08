<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class SectionController extends Controller
{
    public function index(int $articleId, string $sectionName): JsonResponse
    {
        return new JsonResponse([
            'body' => "<h1>Dummy Response $articleId $sectionName</h1>",
        ]);
    }
}
