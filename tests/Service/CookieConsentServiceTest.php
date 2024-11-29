<?php

namespace huppys\CookieConsentBundle\tests\Service;

use huppys\CookieConsentBundle\Enum\ConsentType;
use huppys\CookieConsentBundle\Enum\CookieName;
use huppys\CookieConsentBundle\Form\ConsentCategoryTypeModel;
use huppys\CookieConsentBundle\Form\ConsentDetailedTypeModel;
use huppys\CookieConsentBundle\Form\ConsentVendorTypeModel;
use huppys\CookieConsentBundle\Service\CookieConsentService;
use huppys\CookieConsentBundle\tests\Fixtures\Configuration\ConsentBundleConfiguration;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CookieConsentServiceTest extends TestCase
{
    private CookieConsentService $consentService;


    public function setUp(): void
    {
        $this->consentService = new CookieConsentService($this->getConsentCookieConfiguration(), false);
    }

    #[Test]
    public function shouldReturnCookieWithValueFalseAfterRejectingConsent(): void
    {
        $request = $this->createMock(Request::class);
        $headerBag = $this->consentService->rejectAllCookies($request);

        $this->assertInstanceOf(ResponseHeaderBag::class, $headerBag);
        $this->assertEquals(ConsentType::NO_CONSENT, $headerBag->getCookies()[0]->getValue());
        $this->assertEquals(CookieName::COOKIE_CONSENT_NAME, $headerBag->getCookies()[0]->getName());
    }

    #[Test]
    public function shouldReturnCookieWithValueTrueAfterGivingConsent(): void
    {
        $request = $this->createMock(Request::class);
        $headerBag = $this->consentService->acceptAllCookies([], $request);

        $this->assertInstanceOf(ResponseHeaderBag::class, $headerBag);
        $this->assertEquals(ConsentType::FULL_CONSENT, $headerBag->getCookies()[0]->getValue());
        $this->assertEquals(CookieName::COOKIE_CONSENT_NAME, $headerBag->getCookies()[0]->getName());
    }

    #[Test]
    public function shouldCreateDetailedFormModelFromConfiguration(): void
    {
        $formModel = $this->consentService->createDetailedForm();

        $this->assertInstanceOf(ConsentDetailedTypeModel::class, $formModel);

        $configuredCategories = $this->getConsentCookieConfiguration()['consent_categories'];

        /** @var ConsentCategoryTypeModel $category */
        foreach ($formModel->getCategories() as $category) {
            $this->assertArrayHasKey($category->getName(), $configuredCategories);

            /** @var ConsentVendorTypeModel $vendor */
            foreach ($category->getVendors() as $vendor) {
                $this->assertContains($vendor->getName(), $configuredCategories[$category->getName()]);
            }
        }
    }

    private function getConsentCookieConfiguration()
    {
        return ConsentBundleConfiguration::testCaseConfiguration()['consent_configuration'];
    }
}
