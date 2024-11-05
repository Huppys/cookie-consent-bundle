<?php

namespace huppys\CookieConsentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cookieconsent_settings")]
class CookieSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int $id;

    #[ORM\OneToOne]
    private BrowserCookie $consentCookie;

    public function __construct(BrowserCookie $consentCookie)
    {
        $this->consentCookie = $consentCookie;
    }

    public function getConsentCookie(): BrowserCookie
    {
        return $this->consentCookie;
    }
}