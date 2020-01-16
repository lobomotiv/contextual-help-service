<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\NotFoundArticle;
use App\Exceptions\NotFoundSection;

class ZendeskMapper
{
    /**
     * @var array
     */
    private $articleMap;

    /**
     * @var array
     */
    private $sectionMap;

    public function __construct()
    {
        $this->articleMap = config('zendesk.articleMap');
        $this->sectionMap = config('zendesk.sectionMap');
    }

    public function getZendeskArticleId(string $stringId): int
    {
        $this->validateArticleId($stringId);

        return $this->articleMap[$stringId];
    }

    public function getZendeskSectionId(int $articleId, string $sectionName)
    {
        $this->validateSectionName($articleId, $sectionName);

        return $this->sectionMap[$articleId][$sectionName];
    }

    private function validateArticleId(string $stringId): void
    {
        if ($stringId === '') {
            throw new NotFoundArticle('Article id must be a non empty string');
        }

        if (!array_key_exists($stringId, $this->articleMap)) {
            throw new NotFoundArticle(sprintf('Article with id %s does not found', $stringId));
        }
    }
    private function validateSectionName(int $articleId, string $sectionName): void
    {
        if ($sectionName === '') {
            throw new NotFoundSection('Section id must be a non empty string');
        }

        $sectionMapHasArticle = array_key_exists($articleId, $this->sectionMap);
        $sectionMapArticleHasSection = array_key_exists($sectionName, $this->sectionMap[$articleId]);

        if (!$sectionMapHasArticle || !$sectionMapArticleHasSection) {
            throw new NotFoundSection(sprintf('Section with id %s does not found', $sectionName));
        }
    }
}
