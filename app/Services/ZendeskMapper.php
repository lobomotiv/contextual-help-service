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
    private $map;

    public function __construct()
    {
        $this->map = config('zendesk.map');
    }

    public function getZendeskArticleId(string $stringId): int
    {
        $this->validateArticleId($stringId);

        return $this->map[$stringId]['articleId'];
    }

    public function getZendeskSectionId(string $articleId, string $sectionName)
    {
        $this->validateArticleIdAndSectionName($articleId, $sectionName);

        return $this->map[$articleId]['sections'][$sectionName];
    }

    private function validateArticleId(string $stringId): void
    {
        if ($stringId === '') {
            throw new NotFoundArticle('Article id must be a non empty string');
        }

        if (!array_key_exists($stringId, $this->map) || !array_key_exists('articleId', $this->map[$stringId])) {
            throw new NotFoundArticle(sprintf('Article with id %s does not found', $stringId));
        }
    }
    private function validateArticleIdAndSectionName(string $articleId, string $sectionName): void
    {
        $this->validateArticleId($articleId);

        if ($sectionName === '') {
            throw new NotFoundSection('Section id must be a non empty string');
        }

        if (!array_key_exists('sections', $this->map[$articleId]) || !array_key_exists($sectionName, $this->map[$articleId]['sections'])) {
            throw new NotFoundSection(sprintf('Section with id %s does not found', $sectionName));
        }
    }
}
