<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\tests\Twig;

use huppys\CookieConsentBundle\Twig\CookieConsentTwigExtension;
use PHPUnit\Framework\TestCase;

class CookieConsentTwigExtensionTest extends TestCase
{
    private CookieConsentTwigExtension $CookieConsentTwigExtension;

    public function setUp(): void
    {
        $this->CookieConsentTwigExtension = new CookieConsentTwigExtension();
    }

//    public function testIsCookieConsentOptionSetByUser(): void
//    {
//        $request  = new Request();
//
//        $appVariable = $this->createMock(AppVariable::class);
//        $appVariable
//            ->expects($this->once())
//            ->method('getRequest')
//            ->wilLReturn($request);
//
//        $context = ['app' => $appVariable];
//        $result  = $this->CookieConsentTwigExtension->isCookieConsentOptionSetByUser($context);
//
//        $this->assertSame($result, false);
//    }
//
//    public function testIsCategoryAllowedByUser(): void
//    {
//        $request  = new Request();
//
//        $appVariable = $this->createMock(AppVariable::class);
//        $appVariable
//            ->expects($this->once())
//            ->method('getRequest')
//            ->wilLReturn($request);
//
//        $context = ['app' => $appVariable];
//        $result  = $this->CookieConsentTwigExtension->isCategoryAllowedByUser($context, 'analytics');
//
//        $this->assertSame($result, false);
//    }
}
