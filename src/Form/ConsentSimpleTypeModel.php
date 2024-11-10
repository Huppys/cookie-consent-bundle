<?php

namespace huppys\CookieConsentBundle\Form;

use huppys\CookieConsentBundle\Enum\FormSubmitName;

class ConsentSimpleTypeModel
{
    private bool $rejectAll;
    private bool $acceptAll;

    /**
     * @param string[] $formData
     */
    public function __construct(array $formData)
    {
        $this->rejectAll = $formData[FormSubmitName::REJECT_ALL] ?? false;
        $this->acceptAll = $formData[FormSubmitName::ACCEPT_ALL] ?? false;
    }

    public function isRejectAll(): bool
    {
        return $this->rejectAll;
    }

    public function isAcceptAll(): bool
    {
        return $this->acceptAll;
    }
}