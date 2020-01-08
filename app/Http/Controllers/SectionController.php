<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class SectionController extends Controller
{
    /**
     * @var \HTMLPurifier
     */
    private $purifier;

    public function __construct(\HTMLPurifier $purifier)
    {
        $this->purifier = $purifier;
    }

    public function index(int $articleId, string $sectionName): JsonResponse
    {
        $responseBody = "<script>alert('test')</script><h1>Dummy Response ${articleId} ${sectionName}</h1>";
        return new JsonResponse([
            'body' => $this->purifier->purify($responseBody),
        ]);
    }
}
