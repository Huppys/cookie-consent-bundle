<?php

namespace huppys\CookieConsentBundle\tests\Bundle;

use huppys\CookieConsentBundle\tests\Fixtures\Configuration\ConsentBundleConfiguration;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CookieConsentBundleWebTest extends WebTestCase
{
    private Crawler $crawler;

    public function setUp(): void
    {
        $client = static::createClient();

        $this->crawler = $client->request('GET', '/cookie-consent/view');

        $this->assertResponseIsSuccessful();
    }


    #[Test]
    public function shouldRenderConsentFormInDialog()
    {
        // assert simple form and detailed form are rendered
        $this->assertSelectorExists('dialog .cookie-consent-simple');
        $this->assertSelectorExists('dialog .cookie-consent-detail');
    }

    #[Test]
    public function shouldRenderDetailedConsentFormWithConsentCategories(): void
    {
        // expect the form to render a .consent-form-categories element
        $this->assertSelectorCount(1, '.cookie-consent-detail .consent-form-categories');

        // expect the form to contain as many .consent-form-category elements as consent_categories are defined by the bundle config
        $this->assertSelectorCount(sizeof(ConsentBundleConfiguration::kernelTestCaseConfiguration()['consent_configuration']['consent_categories']), '.consent-form-categories .consent-form-category');

        // expect form to contain the same amount of .consent-form-vendors as defined in the bundle config
        $this->assertSelectorCount(3, '.consent-form-category .consent-form-vendors');

        // assert detailed form contains all categories set in bundle config
        // TODO: Find a nice way to check if the consent categories from the bundle settings are parts of the consent form
//        foreach (ConsentBundleConfiguration::kernelTestCaseConfiguration()['consent_configuration']['consent_categories'] as $key => $category) {
//            $this->assertSelectorExists('.consent-form-category input[name*=' . $key . ']');
//            $this->assertSelectorExists('.cookie-consent-detail .category-' . $key);
//        }
    }
}