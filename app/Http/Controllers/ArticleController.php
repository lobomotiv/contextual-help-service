<?php

namespace App\Http\Controllers;

use App\Clients\ZendeskClient;
use App\Exceptions\NotFoundArticle;
use HTMLPurifier;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    private $purifier;
    private $client;

    public function __construct(HTMLPurifier $purifier, ZendeskClient $client)
    {
        $this->purifier = $purifier;
        $this->client = $client;
    }

    public function index(int $id): JsonResponse
    {
        try {
            $article = $this->client->getArticleById($id);

            return new JsonResponse([
                'body' => $this->purifier->purify($article['body']),
                'url' => $article['html_url'],
                'title' => $article['title'],
            ]);
        } catch (NotFoundArticle $exception) {
            return new JsonResponse(null, 404);
        }
    }
}
