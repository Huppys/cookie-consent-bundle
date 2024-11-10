<?php

namespace huppys\CookieConsentBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ConsentDetailedTypeModel
{
    protected Collection $categories;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): void
    {
        $this->categories = $categories;
    }
}