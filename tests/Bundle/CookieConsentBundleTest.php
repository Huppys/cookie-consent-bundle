<?php

namespace huppys\CookieConsentBundle\tests\Bundle;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class CookieConsentBundleTest extends KernelTestCase
{

    public function setUp(): void
    {
        self::bootKernel();
    }

    #[Test]
    public function shouldExtendAbstractBundle(): void
    {
        $bundle = static::$kernel->getBundle('CookieConsentBundle');
        $this->assertInstanceOf(AbstractBundle::class, $bundle);
    }
}
