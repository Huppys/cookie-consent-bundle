<?php

namespace huppys\CookieConsentBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ConsentDetailedConfiguration
{
    protected string $description;
    protected Collection $categories;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setCategories(Collection $categories): void
    {
        $this->categories = $categories;
    }
}