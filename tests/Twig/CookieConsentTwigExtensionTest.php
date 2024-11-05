<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\tests\Twig;

use huppys\CookieConsentBundle\Service\CookieConsentService;
use huppys\CookieConsentBundle\Twig\CookieConsentTwigExtension;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CookieConsentTwigExtensionTest extends TestCase
{
    private CookieConsentTwigExtension $cookieConsentTwigExtension;
    private MockObject $cookieConsentService;
    private MockObject $requestStack;

    public function setUp(): void
    {
        $this->cookieConsentService = $this->createMock(CookieConsentService::class);
        $this->requestStack = $this->createMock(RequestStack::class);

        $this->requestStack
            ->expects($this->any())
            ->method('getCurrentRequest')
            ->willReturn(new Request());

        $this->cookieConsentTwigExtension = new CookieConsentTwigExtension($this->cookieConsentService, $this->requestStack);
    }

    #[Test]
    public function shouldCheckCookieConsentOptionIsSetByUser(): void
    {
        $result = $this->cookieConsentTwigExtension->isCookieConsentOptionSetByUser([]);

        $this->assertSame($result, false);
    }

    #[Test]
    public function testIsCategoryAllowedByUser(): void
    {
        $result = $this->cookieConsentTwigExtension->isCategoryAllowedByUser('analytics', []);

        $this->assertSame($result, false);
    }
}
