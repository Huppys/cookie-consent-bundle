<?php

namespace huppys\CookieConsentBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cookieconsent_cookies")]
class BrowserCookie
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: TYPES::STRING ,length: 255)]
    private string $name;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $httpOnly;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $secure;

    #[ORM\Column(type: Types::BOOLEAN)]
    private string $sameSite;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private string $expires;

    #[ORM\Column(type: Types::STRING)]
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}