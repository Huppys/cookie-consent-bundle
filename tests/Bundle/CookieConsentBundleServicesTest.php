<?php

namespace huppys\CookieConsentBundle\tests\Bundle;

use huppys\CookieConsentBundle\Controller\CookieConsentController;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CookieConsentBundleServicesTest extends KernelTestCase
{

    public function setUp(): void
    {
        self::bootKernel();
    }

    #[Test]
    public function shouldProvideController(): void
    {
        $this->assertInstanceOf(CookieConsentController::class, static::getContainer()->get(CookieConsentController::class));
    }
}
