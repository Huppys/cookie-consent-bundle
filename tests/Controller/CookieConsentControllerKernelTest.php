<?php

namespace huppys\CookieConsentBundle\tests\Controller;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CookieConsentControllerKernelTest extends WebTestCase
{
    #[Test]
    public function shouldRenderTemplateOnRequest(): void
    {
        $client = static::createClient();

        $client->request('GET', '/cookie-consent/view');

        $this->assertResponseIsSuccessful();
    }

    #[Test]
    public function shouldBlockGetRequestForUpdatingConsentSettings(): void
    {
        $this->markTestIncomplete();
    }
}