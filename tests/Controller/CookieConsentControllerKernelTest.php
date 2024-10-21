<?php

namespace huppys\CookieConsentBundle\tests\Controller;

use huppys\CookieConsentBundle\Enum\CookieName;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CookieConsentControllerKernelTest extends WebTestCase
{
    #[Test]
    public function shouldRenderTemplateOnRequest(): void
    {
        $this->givenSuccessfulRequest();
    }

    #[Test]
    public function shouldSubmitRequestForSimpleConsentSettingsAsPost(): void
    {
        $crawler = $this->givenSuccessfulRequest();

        $form = $crawler->selectButton('consent_simple[accept_all]')->form();

        $this->assertEquals('POST', $form->getMethod());

        static::getClient()->submit($form, ['consent_simple[accept_all]' => true]);

        $this->assertResponseIsSuccessful();
    }

    #[Test]
    public function shouldRejectAllCookiesAndReturnCookieHeader(): void
    {
        $crawler = $this->givenSuccessfulRequest();

        $form = $crawler->selectButton('consent_simple[reject_all]')->form();

        $this->assertEquals('POST', $form->getMethod());

        static::getClient()->submit($form, ['consent_simple[reject_all]' => true]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseCookieValueSame(CookieName::COOKIE_CONSENT_NAME, 'false');
    }

    #[Test]
    public function shouldSubmitRequestForDetailedConsentSettingsAsPost(): void
    {
        $crawler = $this->givenSuccessfulRequest();

        $form = $crawler->selectButton('consent_detailed[accept_all]')->form();

        $this->assertEquals('POST', $form->getMethod());

        static::getClient()->submit($form, ['consent_detailed[accept_all]' => true]);

        $this->assertResponseIsSuccessful();
    }

    /**
     * @return void
     */
    private function givenSuccessfulRequest(): Crawler
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cookie-consent/view');

        $this->assertResponseIsSuccessful();

        return $crawler;
    }
}