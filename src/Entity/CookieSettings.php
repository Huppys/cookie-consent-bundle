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

    #[ORM\OneToOne]
    private BrowserCookie $consentCategoriesCookie;

    public function __construct(string $namePrefix, BrowserCookie $consentCookie, BrowserCookie $consentKeyCookie, BrowserCookie $consentCategoriesCookie)
    {
        $this->namePrefix = $namePrefix;
        $this->consentCookie = $consentCookie;
        $this->consentKeyCookie = $consentKeyCookie;
        $this->consentCategoriesCookie = $consentCategoriesCookie;
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

    public function getConsentCategoriesCookie(): BrowserCookie
    {
        return $this->consentCategoriesCookie;
    }
}