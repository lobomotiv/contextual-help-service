<?php

namespace Test\Unit\Services;

use App\Exceptions\NotFoundArticle;
use App\Services\ZendeskArticleIdMapper;
use Test\TestCase;

class ZendeskArticleIdMapperTest extends TestCase
{
    private const TEST_ZENDESK_ID = 115002923749;
    private const TEST_SUITE_STRING_ID = 'email_campaigns';
    private const TEST_NON_EXISTING_SUITE_STRING_ID = 'non_existing';

    /**
     * @var ZendeskArticleIdMapper
     */
    private $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('zendesk.map', [self::TEST_SUITE_STRING_ID => self::TEST_ZENDESK_ID]);
        $this->mapper = new ZendeskArticleIdMapper();
    }

    /**
     * @test
     */
    public function getZendeskId_calledWithEmptyString_throwsNotFoundArticleException()
    {
        $this->expectException(NotFoundArticle::class);

        $this->mapper->getZendeskId('');
    }

    /**
     * @test
     */
    public function getZendeskId_calledWithExistingSuiteStringId_returnZendeskArticleId()
    {
        $zendeskId = $this->mapper->getZendeskId(self::TEST_SUITE_STRING_ID);

        $this->assertEquals(self::TEST_ZENDESK_ID, $zendeskId);
    }

    /**
     * @test
     */
    public function getZendeskId_calledWithNonExistingSuiteStringId_throwsNotFoundArticleException()
    {
        $this->expectException(NotFoundArticle::class);

        $this->mapper->getZendeskId(self::TEST_NON_EXISTING_SUITE_STRING_ID);
    }

}
