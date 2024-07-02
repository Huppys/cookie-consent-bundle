<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use huppys\CookieConsentBundle\Entity\CookieConsentLog;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CookieConsentRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(private ManagerRegistry $entityManagerRegistry)
    {
        $this->entityManager = $this->entityManagerRegistry->getManagerForClass(CookieConsentLog::class);
    }

    public function rejectAllCookies(Request $request, Response $response)
    {
        $cookieLog = new CookieConsentLog();

        $cookieLog->setCookieName();
        $cookieLog->setCookieValue();
        $cookieLog->setConsentKey();
        $cookieLog->setIpAddress();
        $cookieLog->setTimestamp();

        $this->entityManager->persist($cookieLog);

        $this->entityManager->flush();

        $response->headers->setCookie();
    }
}