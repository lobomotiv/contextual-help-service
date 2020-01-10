<?php

namespace App\Services;

use App\Exceptions\NotFoundArticle;

class ZendeskArticleIdMapper
{
    /**
     * @var array
     */
    private $map;

    public function __construct()
    {
        $this->map = config('zendesk.map');
    }

    public function getZendeskId(string $stringId): int
    {
        $this->validateStringId($stringId);

        return $this->map[$stringId];
    }

    private function validateStringId(string $stringId): void
    {
        if ($stringId === '') {
            throw new NotFoundArticle('Article id must be a non empty string');
        }

        if (!array_key_exists($stringId, $this->map)) {
            throw new NotFoundArticle(sprintf('Article with id %s does not found', $stringId));
        }
    }
}
