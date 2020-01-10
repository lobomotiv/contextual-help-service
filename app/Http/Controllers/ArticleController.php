<?php

namespace App\Http\Controllers;

use App\Clients\ZendeskClient;
use App\Exceptions\NotFoundArticle;
use App\Services\ZendeskArticleIdMapper;
use HTMLPurifier;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    private $purifier;
    private $client;
    private $mapper;

    public function __construct(HTMLPurifier $purifier, ZendeskClient $client, ZendeskArticleIdMapper $mapper)
    {
        $this->purifier = $purifier;
        $this->client = $client;
        $this->mapper = $mapper;
    }

    public function index($id): JsonResponse
    {
        try {
            $articleId = $this->getArticleId($id);
            $article = $this->client->getArticleById($articleId);

            return new JsonResponse([
                'body' => $this->purifier->purify($article['body']),
                'url' => $article['html_url'],
                'title' => $article['title'],
            ]);
        } catch (NotFoundArticle $exception) {
            return new JsonResponse(null, 404);
        }
    }

    private function getArticleId($id): int
    {
        if (!is_numeric($id)) {
            return $this->mapper->getZendeskId($id);
        }

        return (int) $id;
    }
}
