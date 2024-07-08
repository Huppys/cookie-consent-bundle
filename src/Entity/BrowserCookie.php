<?php

namespace huppys\CookieConsentBundle\Entity;

class BrowserCookie
{
    private string $name;
    private bool $httpOnly;
    private bool $secure;
    private string $sameSite;
    private string $expires;
    private ?string $domain;

    public function __construct(string $name, string $expires, ?string $domain, bool $secure, bool $httpOnly, string $sameSite)
    {
        $this->name = $name;
        $this->expires = $expires;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
        $this->sameSite = $sameSite;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    public function isSecure(): bool
    {
        return $this->secure;
    }

    public function getSameSite(): string
    {
        return $this->sameSite;
    }

    public function getExpires(): string
    {
        return $this->expires;
    }

    public function getDomain()
    {
        return $this->domain;
    }
}