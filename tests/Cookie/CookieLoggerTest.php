<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\tests\Cookie;

use Doctrine\ORM\EntityManagerInterface;
use huppys\CookieConsentBundle\Cookie\CookieLogger;
use huppys\CookieConsentBundle\Entity\CookieConsentLog;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class CookieLoggerTest extends TestCase
{
    private MockObject $registry;
    private MockObject $request;
    private MockObject $entityManager;
    private CookieLogger $cookieLogger;

    public function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->request = $this->createMock(Request::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->registry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->with(CookieConsentLog::class)
            ->willReturn($this->entityManager);

        $this->cookieLogger = new CookieLogger($this->registry, $this->request);
    }


    #[Test]
    public function shouldLog(): void
    {
        $this->request
            ->expects($this->once())
            ->method('getClientIp')
            ->willReturn('127.0.0.1');

        $this->entityManager
            ->expects($this->exactly(3))
            ->method('persist');

        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->with();

        $this->cookieLogger->log([
                                     'analytics' => 'true',
                                     'social_media' => 'true',
                                     'tracking' => 'false',
                                 ], 'key-test');
    }


    #[Test]
    public function shouldLogWithNullIp(): void
    {
        $this->request
            ->expects($this->once())
            ->method('getClientIp')
            ->willReturn(null);

        $this->entityManager
            ->expects($this->exactly(3))
            ->method('persist');

        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->with();

        $this->cookieLogger->log([
                                     'analytics' => 'true',
                                     'social_media' => 'true',
                                     'tracking' => 'false',
                                 ], 'key-test');
    }


    #[Test]
    public function shouldNotLogWithoutRequest(): void
    {
        $this->expectException(RuntimeException::class);
        $this->cookieLogger = new CookieLogger($this->registry, null);
        $this->cookieLogger->log([], 'key-test');
    }
}
