<?php

namespace huppys\CookieConsentBundle\Form;

class ConsentDetailsType
{
    private string $name;
    private bool $consentGiven;
    private string $descriptionKey;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getConsentGiven(): bool
    {
        return $this->consentGiven;
    }

    public function setConsentGiven(bool $consentGiven): void
    {
        $this->consentGiven = $consentGiven;
    }

    public function getDescriptionKey(): string
    {
        return $this->descriptionKey;
    }

    public function setDescriptionKey(string $descriptionKey): void
    {
        $this->descriptionKey = $descriptionKey;
    }
}