<?php

namespace huppys\CookieConsentBundle\tests\Service;

use huppys\CookieConsentBundle\Enum\CookieName;
use huppys\CookieConsentBundle\Service\CookieConsentService;
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
        $this->assertEquals('false', $headerBag->getCookies()[0]->getValue());
        $this->assertEquals(CookieName::COOKIE_CONSENT_NAME, $headerBag->getCookies()[0]->getName());
    }

    #[Test]
    public function shouldReturnCookieWithValueTrueAfterGivingConsent(): void
    {
        $request = $this->createMock(Request::class);
        $headerBag = $this->consentService->acceptAllCookies([], $request);

        $this->assertInstanceOf(ResponseHeaderBag::class, $headerBag);
        $this->assertEquals('true', $headerBag->getCookies()[0]->getValue());
        $this->assertEquals(CookieName::COOKIE_CONSENT_NAME, $headerBag->getCookies()[0]->getName());
    }

    private function getConsentCookieConfiguration()
    {
        return [
            'consent_cookie' => [
                'name' => 'consent',
                'http_only' => true,
                'secure' => true,
                'same_site' => 'lax',
                'expires' => 'P180D',
            ]
        ];
    }
}
