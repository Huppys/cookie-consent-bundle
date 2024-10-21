<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Cookie;

use DateInterval;
use DateTime;
use Exception;
use huppys\CookieConsentBundle\Entity\BrowserCookie;
use huppys\CookieConsentBundle\Entity\CookieSettings;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CookieHandler
{
    private CookieSettings $cookieSettings;

    public function __construct(array $cookieSettings)
    {
        $this->cookieSettings = $this->castConfigToCookieSettings($cookieSettings);
    }

    private function castConfigToCookieSettings(array $config): CookieSettings
    {
        return new CookieSettings(
            $config['name_prefix'],
            $this->castCookieConfigToCookieSetting($config['cookies']['consent_cookie']),
            $this->castCookieConfigToCookieSetting($config['cookies']['consent_key_cookie']),
        );
    }

    private function castCookieConfigToCookieSetting(array $config): BrowserCookie
    {
        return new BrowserCookie(
            $config['name'],
            $config['expires'],
            $config['domain'],
            $config['secure'],
            $config['http_only'],
            $config['same_site'],
        );
    }

    /**
     * Save chosen cookie categories in cookies.
     * @throws Exception
     */
    public function save(array $categories, string $cookieConsentKey, Response $response, bool $rejectAllCookies): void
    {
        $consentCookie = $this->cookieSettings->getConsentCookie();
        $this->saveCookie($consentCookie->getName(), date('r'), $consentCookie->getExpires(), $consentCookie->getDomain(),
            $consentCookie->isSecure(), $consentCookie->isHttpOnly(), $consentCookie->getSameSite(), $response);

        if ($rejectAllCookies) {
            return;
        }

        $consentKeyCookie = $this->cookieSettings->getConsentKeyCookie();
        $this->saveCookie($consentKeyCookie->getName(), $cookieConsentKey, $consentKeyCookie->getExpires(), $consentKeyCookie->getDomain(),
            $consentKeyCookie->isSecure(), $consentKeyCookie->isHttpOnly(), $consentKeyCookie->getSameSite(), $response);
    }

    /**
     * Add cookie to response headers.
     * @param string $name
     * @param string $value
     * @param string $expires
     * @param string|null $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @param string $sameSite
     * @param Response $response
     * @throws Exception
     */
    protected function saveCookie(string   $name,
                                  string   $value,
                                  string   $expires,
                                  ?string  $domain,
                                  bool     $secure,
                                  bool     $httpOnly,
                                  string   $sameSite,
                                  Response $response): void
    {
        $expirationDate = new DateTime();
        $expirationDate->add(new DateInterval($expires));

        if (strtolower($sameSite) == 'none') {
            $secure = true;
        }

        $response->headers->setCookie(
            new Cookie($this->cookieSettings->getNamePrefix() . $name, $value, $expirationDate, '/', $domain, $secure, $httpOnly, true, $sameSite)
        );
    }
}
