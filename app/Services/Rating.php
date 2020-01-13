<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\RatingNotFound;
use Illuminate\Redis\RedisManager;

class Rating
{
    private $redisManager;

    public function __construct(RedisManager $redisManager)
    {
        $this->redisManager = $redisManager;
    }

    public function upVote(int $articleId, int $customerId, int $adminId): void
    {
        $this->redisManager->set("${articleId}.${customerId}.${adminId}", 'up');
    }

    public function downVote(int $articleId, int $customerId, int $adminId): void
    {
        $this->redisManager->set("${articleId}.${customerId}.${adminId}", 'down');
    }

    public function deleteVote(int $articleId, int $customerId, int $adminId): void
    {
        $deletedKeysCount = $this->redisManager->delete("${articleId}.${customerId}.${adminId}");

        if ($deletedKeysCount === 0) {
            throw new RatingNotFound(sprintf(
                'Rating not found for given key: %d.%d.%d',
                $articleId,
                $customerId,
                $adminId
            ));
        }
    }

    public function getVote(int $articleId, int $customerId, int $adminId): string
    {
        $result = $this->redisManager->get("${articleId}.${customerId}.${adminId}");

        if ($result === null) {
            throw new RatingNotFound(sprintf(
                'Rating not found for given key: %d.%d.%d',
                $articleId,
                $customerId,
                $adminId
            ));
        }

        return $result;
    }
}
