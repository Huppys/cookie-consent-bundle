<?php

namespace huppys\CookieConsentBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cookieconsent_settings")]
class CookieSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $namePrefix;

    #[ORM\OneToOne]
    private BrowserCookie $consentCookie;

    #[ORM\OneToOne]
    private BrowserCookie $consentKeyCookie;

    public function __construct(string $namePrefix, BrowserCookie $consentCookie, BrowserCookie $consentKeyCookie)
    {
        $this->namePrefix = $namePrefix;
        $this->consentCookie = $consentCookie;
        $this->consentKeyCookie = $consentKeyCookie;
    }

    public function getNamePrefix(): string
    {
        return $this->namePrefix;
    }

    public function getConsentCookie(): BrowserCookie
    {
        return $this->consentCookie;
    }

    public function getConsentKeyCookie(): BrowserCookie
    {
        return $this->consentKeyCookie;
    }
}