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

    public function getZendeskId(string $suiteStringId)
    {
        $this->validateSuiteStringId($suiteStringId);

        return $this->map[$suiteStringId];
    }

    private function validateSuiteStringId(string $suiteStringId): void
    {
        if ($suiteStringId === '') {
            throw new NotFoundArticle('Article id must be a non empty string');
        }

        if (!array_key_exists($suiteStringId, $this->map)) {
            throw new NotFoundArticle(printf('Article with id %s does not found', $suiteStringId));
        }
    }
}
