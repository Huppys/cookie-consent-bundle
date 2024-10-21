<?php

namespace huppys\CookieConsentBundle\tests\Mapper;

use DateInterval;
use huppys\CookieConsentBundle\Mapper\CookieConfigMapper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CookieConfigMapperTest extends TestCase
{
    #[Test]
    public function shouldReturnDateTime():void {
        $expire = CookieConfigMapper::convertExpireToDate('P180D');
        $this->assertInstanceOf(\DateTimeInterface::class, $expire);
    }

    #[Test]
    public function shouldConvertDateIntervalAsStringToDatetime(): void {
        $interval = new DateInterval('P180D');
        $now = new \DateTimeImmutable();

        $later = $now->add($interval);

        $this->assertGreaterThan($now, $later);
    }
}
