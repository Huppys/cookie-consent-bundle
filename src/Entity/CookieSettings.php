<?php

namespace huppys\CookieConsentBundle\Entity;

class CookieSettings
{
    private string $namePrefix;
    private BrowserCookie $consentCookie;
    private BrowserCookie $consentKeyCookie;
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