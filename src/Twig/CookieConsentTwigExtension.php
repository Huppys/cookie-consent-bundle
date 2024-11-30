<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\Twig;

use huppys\CookieConsentBundle\Service\CookieConsentService;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CookieConsentTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly CookieConsentService $cookieConsentService,
        private readonly RequestStack         $requestStack)
    {
    }

    /**
     * Register all custom twig functions.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'cookieconsent_isCookieConsentOptionSetByUser',
                [$this, 'isCookieConsentOptionSetByUser'],
                ['needs_context' => false]
            ),
            new TwigFunction(
                'cookieconsent_isCategoryAllowedByUser',
                [$this, 'isCategoryAllowedByUser'],
                ['needs_context' => false]
            ),
            new TwigFunction(
                'cookieconsent_isVendorAllowedByUser',
                [$this, 'isVendorAllowedByUser'],
                ['needs_context' => false]
            ),
        ];
    }

    /**
     * Checks if user has sent cookie consent form.
     */
    public function isCookieConsentOptionSetByUser(): bool
    {
        return $this->cookieConsentService->isCookieConsentFormSubmittedByUser($this->requestStack->getCurrentRequest());
    }

    /**
     * Checks if user has given permission for cookie category.
     */
    public function isCategoryAllowedByUser(string $category): bool
    {
        return $this->cookieConsentService->isCategoryAllowedByUser($category, $this->requestStack->getCurrentRequest());
    }

    /**
     * Checks if user has given permission for cookie category.
     */
    public function isVendorAllowedByUser(string $vendor, string $category): bool
    {
        return $this->cookieConsentService->isVendorAllowedByUser($vendor, $category, $this->requestStack->getCurrentRequest());
    }
}
