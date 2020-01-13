<?php

namespace Test\Unit\Services;

use App\Exceptions\RatingNotFound;
use App\Services\Rating;
use Illuminate\Redis\RedisManager;
use Test\TestCase;

class RatingTest extends TestCase
{
    private const ARTICLE_ID = 1;
    private const CUSTOMER_ID = 2;
    private const ADMIN_ID = 3;
    private const NOT_EXISTING_ARTICLE_ID = 849;

    /**
     * @var Rating
     */
    private $rating;
    /**
     * @var RedisManager|\PHPUnit\Framework\MockObject\MockObject
     */
    private $redisManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->redisManager = $this->createMock(RedisManager::class);
        $this->rating = new Rating($this->redisManager);
    }

    /**
     * @test
     */
    public function upVote_givenArticleIdAndCustomerIdAndAdminId_callsRedisManagerSet()
    {
        $articleId = self::ARTICLE_ID;
        $customerId = self::CUSTOMER_ID;
        $adminId = self::ADMIN_ID;

        $this->redisManager
            ->expects($this->once())
            ->method('__call')
            ->with('set', ["${articleId}.${customerId}.${adminId}", 'up']);

        $this->rating->upVote($articleId, $customerId, $adminId);
    }

    /**
     * @test
     */
    public function downVote_givenArticleIdAndCustomerIdAndAdminId_callsRedisManagerSet()
    {
        $articleId = self::ARTICLE_ID;
        $customerId = self::CUSTOMER_ID;
        $adminId = self::ADMIN_ID;

        $this->redisManager
            ->expects($this->once())
            ->method('__call')
            ->with('set', ["${articleId}.${customerId}.${adminId}", 'down']);

        $this->rating->downVote($articleId, $customerId, $adminId);
    }

    /**
     * @test
     */
    public function deleteVote_givenNotExistingArticleIdAndCustomerIdAndAdminId_throwsNotFoundException()
    {
        $notExistingArticleId = self::NOT_EXISTING_ARTICLE_ID;
        $customerId = self::CUSTOMER_ID;
        $adminId = self::ADMIN_ID;

        $this->redisManager
            ->expects($this->once())
            ->method('__call')
            ->with('del', ["${notExistingArticleId}.${customerId}.${adminId}"])
            ->willReturn(0);

        $this->expectException(RatingNotFound::class);
        $this->expectExceptionMessage(sprintf(
            'Rating not found for given key: %d.%d.%d',
            $notExistingArticleId,
            $customerId,
            $adminId
        ));

        $this->rating->deleteVote($notExistingArticleId, $customerId, $adminId);
    }

    /**
     * @test
     */
    public function deleteVote_givenArticleIdAndCustomerIdAndAdminId_callsRedisManagerDelete()
    {
        $articleId = self::ARTICLE_ID;
        $customerId = self::CUSTOMER_ID;
        $adminId = self::ADMIN_ID;

        $this->redisManager
            ->expects($this->once())
            ->method('__call')
            ->with('del', ["${articleId}.${customerId}.${adminId}"]);

        $this->rating->deleteVote($articleId, $customerId, $adminId);
    }

    /**
     * @test
     */
    public function getVote_givenNotExistingArticleIdAndCustomerIdAndAdminId_throwsNotFoundException()
    {
        $articleId = self::ARTICLE_ID;
        $customerId = self::CUSTOMER_ID;
        $adminId = self::ADMIN_ID;

        $this->redisManager
            ->expects($this->once())
            ->method('__call')
            ->with('get', ["${articleId}.${customerId}.${adminId}"])
            ->willReturn(null);

        $this->expectException(RatingNotFound::class);
        $this->expectExceptionMessage(sprintf(
            'Rating not found for given key: %d.%d.%d',
            $articleId,
            $customerId,
            $adminId
        ));

        $this->rating->getVote($articleId, $customerId, $adminId);
    }

    /**
     * @test
     */
    public function getVote_givenArticleIdAndCustomerIdAndAdminId_returnsRating()
    {
        $expectedResult = 'up';
        $articleId = self::ARTICLE_ID;
        $customerId = self::CUSTOMER_ID;
        $adminId = self::ADMIN_ID;

        $this->redisManager
            ->expects($this->once())
            ->method('__call')
            ->with('get', ["${articleId}.${customerId}.${adminId}"])
            ->willReturn($expectedResult);

        $result = $this->rating->getVote($articleId, $customerId, $adminId);

        $this->assertEquals($expectedResult, $result);
    }
}
