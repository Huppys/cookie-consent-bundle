<?php

namespace huppys\CookieConsentBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ConsentCategory
{
    private string $name;
    private Collection $cookies;
    private bool $userConsent;

    public function __construct()
    {
        $this->cookies = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCookies(): Collection
    {
        return $this->cookies;
    }

    public function getUserConsent(): bool
    {
        return $this->userConsent;
    }

    public function setUserConsent(bool $userConsent): void
    {
        $this->userConsent = $userConsent;
    }
}