<?php

namespace huppys\CookieConsentBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ConsentCategoryTypeModel
{
    private string $name;
    private Collection $vendors;

    public function __construct()
    {
        $this->vendors = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getVendors(): Collection
    {
        return $this->vendors;
    }
}