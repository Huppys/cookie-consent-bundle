<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Cookie;

use huppys\CookieConsentBundle\Enum\CookieName;
use Symfony\Component\HttpFoundation\RequestStack;

class CookieChecker
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    /**
     * Check if cookie consent has already been saved.
     */
    public function isCookieConsentOptionSetByUser(): bool
    {
        return $this->requestStack->getCurrentRequest()->cookies->has(CookieName::COOKIE_CONSENT_NAME);
    }

    /**
     * Check if given cookie category is permitted by user.
     */
    public function isCategoryAllowedByUser(string $category): bool
    {
        return $this->requestStack->getCurrentRequest()->cookies->get(CookieName::getCookieCategoryName($category)) === 'true';
    }
}
