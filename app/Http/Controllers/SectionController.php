<?php

namespace App\Http\Controllers;

use App\Clients\ZendeskClient;
use App\Exceptions\NotFoundArticle;
use HTMLPurifier;
use Illuminate\Http\JsonResponse;
use Symfony\Component\DomCrawler\Crawler;

class SectionController extends Controller
{
    /**
     * @var HTMLPurifier
     */
    private $purifier;
    /**
     * @var ZendeskClient
     */
    private $zendeskClient;
    /**
     * @var Crawler
     */
    private $crawler;

    public function __construct(HTMLPurifier $purifier, ZendeskClient $zendeskClient, Crawler $crawler)
    {
        $this->purifier = $purifier;
        $this->zendeskClient = $zendeskClient;
        $this->crawler = $crawler;
    }

    public function index(int $articleId, string $sectionName): JsonResponse
    {
        try {
            $article = $this->zendeskClient->getArticleById($articleId);
        } catch (NotFoundArticle $exception) {
            return new JsonResponse(null, 404);
        }

        $this->crawler->addHtmlContent($article['body']);
        $sectionCrawler = $this->crawler->filterXPath(sprintf('//div[@id="%s"]', $sectionName));

        if ($sectionCrawler->count() === 0) {
            return new JsonResponse(null, 404);
        }

        $section = $sectionCrawler->outerHtml();

        return new JsonResponse([
            'body' => $this->purifier->purify($section),
        ]);
    }
}
