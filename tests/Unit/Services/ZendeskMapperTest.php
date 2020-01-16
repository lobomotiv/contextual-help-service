<?php

namespace Test\Unit\Services;

use App\Exceptions\NotFoundArticle;
use App\Exceptions\NotFoundSection;
use App\Services\ZendeskMapper;
use Test\TestCase;

class ZendeskMapperTest extends TestCase
{
    private const TEST_ZENDESK_ID = 115002923749;
    private const TEST_SECTION_ID = 'section_id';
    private const TEST_SUITE_STRING_ID = 'email_campaigns';
    private const TEST_SUITE_INVALID_STRING_ID = 'ac_programs';
    private const TEST_NON_EXISTING_SUITE_STRING_ID = 'non_existing';
    private const TEST_SUITE_SECTION_NAME = 'sectionName';

    /**
     * @var ZendeskMapper
     */
    private $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('zendesk.map', [
            self::TEST_SUITE_STRING_ID => [
                'articleId' => self::TEST_ZENDESK_ID,
                'sections' => [
                    self::TEST_SUITE_SECTION_NAME => self::TEST_SECTION_ID
                ],
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

    /**
     * @test
     */
    public function getZendeskSectionId_calledWithEmptyArticleIdAndEmptySectionName_throwsNotFoundArticleException()
    {
        $this->expectException(NotFoundArticle::class);

        $this->mapper->getZendeskSectionId('', '');
    }

    /**
     * @test
     */
    public function getZendeskSectionId_calledWithArticleIdAndEmptySectionName_throwsNotFoundSectionException()
    {
        $this->expectException(NotFoundSection::class);

        $this->mapper->getZendeskSectionId(self::TEST_SUITE_STRING_ID, '');
    }

    /**
     * @test
     */
    public function getZendeskSectionId_calledWithArticleIdAndInvalidSectionName_throwsNotFoundSectionException()
    {
        $this->expectException(NotFoundSection::class);

        $this->mapper->getZendeskSectionId(self::TEST_SUITE_STRING_ID, 'someSectionName');
    }

    /**
     * @test
     */
    public function getZendeskSectionId_calledWithArticleIdAndSectionName_returnsSectionId()
    {
        $sectionId = $this->mapper->getZendeskSectionId(self::TEST_SUITE_STRING_ID, self::TEST_SUITE_SECTION_NAME);

        $this->assertEquals(self::TEST_SECTION_ID, $sectionId);
    }
}
