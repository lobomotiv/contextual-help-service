<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Clients\ZendeskClient;
use App\Exceptions\NotFoundArticle;
use App\Exceptions\NotFoundSection;
use App\Services\ZendeskMapper;
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
    /**
     * @var ZendeskMapper
     */
    private $mapper;

    public function __construct(
        HTMLPurifier $purifier,
        ZendeskClient $zendeskClient,
        Crawler $crawler,
        ZendeskMapper $mapper)
    {
        $this->purifier = $purifier;
        $this->zendeskClient = $zendeskClient;
        $this->crawler = $crawler;
        $this->mapper = $mapper;
    }

    public function index($articleId, string $sectionName): JsonResponse
    {
        try {
            $zendeskArticleId = $this->getArticleId($articleId);
            $article = $this->zendeskClient->getArticleById($zendeskArticleId);
        } catch (NotFoundArticle $exception) {
            return new JsonResponse(null, 404);
        }

        $sectionName = $this->getSectionNameFromMap($sectionName, $zendeskArticleId);

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

    private function getArticleId($id): int
    {
        if (!is_numeric($id)) {
            return $this->mapper->getZendeskArticleId($id);
        }

        return (int) $id;
    }

    private function getSectionNameFromMap(string $sectionName, int $zendeskArticleId): string
    {
        try {
            $sectionName = $this->mapper->getZendeskSectionId($zendeskArticleId, $sectionName);
        } catch (NotFoundSection $exception) {
        }
        return $sectionName;
    }
}
