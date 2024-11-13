<?php

namespace huppys\CookieConsentBundle\Ui;

use Symfony\Component\Form\FormView;

class ConsentFormDto
{
    public function __construct(
        public readonly FormView    $simpleForm,
        public readonly FormView    $detailedForm,
        public readonly string      $position,
        public readonly string|null $readMoreRoute
    )
    {
    }

    public function toArray(): array
    {
        return [
            'simple_form' => $this->simpleForm,
            'detailed_form' => $this->detailedForm,
            'position' => $this->position,
            'read_more_route' => $this->readMoreRoute,
        ];
    }
}