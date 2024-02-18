<?php

declare(strict_types=1);


namespace huppys\CookieConsentBundle\Twig;

use huppys\CookieConsentBundle\Cookie\CookieChecker;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CookieConsentTwigExtension extends AbstractExtension
{
    public function __construct(private readonly string $cookieConsentTheme)
    {
    }

    /**
     * Register all custom twig functions.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'cookieconsent_isCookieConsentSavedByUser',
                [$this, 'isCookieConsentSavedByUser'],
                ['needs_context' => true]
            ),
            new TwigFunction(
                'cookieconsent_isCategoryAllowedByUser',
                [$this, 'isCategoryAllowedByUser'],
                ['needs_context' => true]
            ),
            new TwigFunction(
                'cookieconsent_getTheme',
                [$this, 'getTheme'],
                ['needs_context' => false]
            ),
        ];
    }

    /**
     * Checks if user has sent cookie consent form.
     */
    public function isCookieConsentSavedByUser(array $context): bool
    {
        $cookieChecker = $this->getCookieChecker($context['app']->getRequest());

        return $cookieChecker->isCookieConsentOptionSetByUser();
    }

    /**
     * Checks if user has given permission for cookie category.
     */
    public function isCategoryAllowedByUser(array $context, string $category): bool
    {
        $cookieChecker = $this->getCookieChecker($context['app']->getRequest());

        return $cookieChecker->isCategoryAllowedByUser($category);
    }

    public function getTheme(): string
    {
        if (isset($this->cookieConsentTheme)) {
            return $this->cookieConsentTheme;
        }

        return 'light'; // default theme
    }

    /**
     * Get instance of CookieChecker.
     */
    private function getCookieChecker(Request $request): CookieChecker
    {
        return new CookieChecker($request);
    }
}
