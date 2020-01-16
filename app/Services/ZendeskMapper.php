<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\NotFoundArticle;

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

    private function validateArticleId(string $stringId): void
    {
        if ($stringId === '') {
            throw new NotFoundArticle('Article id must be a non empty string');
        }

        if (!array_key_exists($stringId, $this->map) || !array_key_exists('articleId', $this->map[$stringId])) {
            throw new NotFoundArticle(sprintf('Article with id %s does not found', $stringId));
        }
    }
}
