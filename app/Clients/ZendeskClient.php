<?php

declare(strict_types=1);

namespace App\Clients;

use App\Exceptions\NotFoundArticle;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ZendeskClient
{
    private const HELP_PORTAL_BASE_URL = 'https://help.emarsys.com';

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getArticleById(int $articleId): array
    {
        try {
            $url = self::HELP_PORTAL_BASE_URL . "/api/v2/help_center/en-us/articles/${articleId}.json";
            $response = $this->client->get($url);
        } catch (ClientException $exception) {
            throw new NotFoundArticle("Article not found with given id: ${articleId}");
        }

        $responseData = json_decode($response->getBody(), true);

        return $responseData['article'];
    }
}
