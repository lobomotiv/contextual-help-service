<?php

namespace Test\Unit\Services;

use App\Exceptions\NotFoundArticle;
use App\Services\ZendeskMapper;
use Test\TestCase;

class ZendeskMapperTest extends TestCase
{
    private const TEST_ZENDESK_ID = 115002923749;
    private const TEST_SUITE_STRING_ID = 'email_campaigns';
    private const TEST_SUITE_INVALID_STRING_ID = 'ac_programs';
    private const TEST_NON_EXISTING_SUITE_STRING_ID = 'non_existing';

    /**
     * @var ZendeskMapper
     */
    private $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('zendesk.map', [
            self::TEST_SUITE_STRING_ID => [
                'articleId' => self::TEST_ZENDESK_ID
            ],
            self::TEST_SUITE_INVALID_STRING_ID => []
        ]);
        $this->mapper = new ZendeskMapper();
    }

    /**
     * @test
     */
    public function getZendeskArticleId_calledWithEmptyString_throwsNotFoundArticleException()
    {
        $this->expectException(NotFoundArticle::class);

        $this->mapper->getZendeskArticleId('');
    }

    /**
     * @test
     */
    public function getZendeskArticleId_calledWithInvalidMapKey_throwsNotFoundArticleException()
    {
        $this->expectException(NotFoundArticle::class);

        $this->mapper->getZendeskArticleId(self::TEST_SUITE_INVALID_STRING_ID);
    }

    /**
     * @test
     */
    public function getZendeskArticleId_calledWithExistingSuiteStringId_returnZendeskArticleId()
    {
        $zendeskId = $this->mapper->getZendeskArticleId(self::TEST_SUITE_STRING_ID);

        $this->assertEquals(self::TEST_ZENDESK_ID, $zendeskId);
    }

    /**
     * @test
     */
    public function getZendeskArticleId_calledWithNonExistingSuiteStringId_throwsNotFoundArticleException()
    {
        $this->expectException(NotFoundArticle::class);

        $this->mapper->getZendeskArticleId(self::TEST_NON_EXISTING_SUITE_STRING_ID);
    }

}
