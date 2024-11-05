<?php

namespace huppys\CookieConsentBundle\Service;

use huppys\CookieConsentBundle\Enum\CookieName;
use huppys\CookieConsentBundle\Mapper\CookieConfigMapper;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CookieConsentService
{
    public function __construct(
        private readonly array $consentConfiguration,
        private readonly bool  $persistConsent)
    {
    }

    /**
     * Check if given cookie category is permitted by user.
     * @param string $category
     * @return bool
     */
    public function isCategoryAllowedByUser(string $category, Request $request): bool
    {
        return $request->cookies->get($category) === 'true';
    }

    /**
     * Check if cookie consent has already been saved.
     * @return bool
     */
    public function isCookieConsentOptionSetByUser(Request $request): bool
    {
        return $request->cookies->has(CookieName::COOKIE_CONSENT_NAME);
    }

    /**
     * @param Request $request
     * @return ResponseHeaderBag
     * @throws InvalidArgumentException
     */
    public function rejectAllCookies(Request $request): ResponseHeaderBag
    {
        // always set value to false as the user didn't give the consent to use more cookies than necessary but we use the 'consent' cookie to hide the UI
        $consentCookie = CookieConfigMapper::mapToCookie($this->consentConfiguration['consent_cookie'], 'false');

        if ($consentCookie == null) {
            throw new InvalidArgumentException("Cookie configuration can't be mapped to a Cookie");
        }

        // save "no-consent" to session
        $this->saveConsentSettingsToSession($request, false);

        // save "no-consent" to db
        $this->persistConsentSettings(false);

        $headerBag = new ResponseHeaderBag();
        $headerBag->setCookie($consentCookie);

        return $headerBag;
    }

    private
    function saveConsentSettingsToSession(Request $request, mixed $value): void
    {
        $session = $request->getSession();

        // save consent settings in session
        $session->set('consent-settings', $value);
    }

    private function persistConsentSettings(mixed $data): void
    {
        if ($this->persistConsent) {
            // TODO: implement method
//        $this->entityManager->persist($cookieLog);

//        $this->entityManager->flush();

            // persist consent log object
        }
    }

    /**
     * @param mixed $getData
     * @param Request $request
     * @return ResponseHeaderBag
     * @throws InvalidArgumentException
     */
    public function acceptAllCookies(mixed $getData, Request $request): ResponseHeaderBag
    {
        // always set value to false as the user didn't give the consent to use more cookies than necessary but we use the 'consent' cookie to hide the UI
        $consentCookie = CookieConfigMapper::mapToCookie($this->consentConfiguration['consent_cookie'], 'true');

        if ($consentCookie == null) {
            throw new InvalidArgumentException("Cookie configuration can't be mapped to a Cookie");
        }

        // save "no-consent" to session
        $this->saveConsentSettingsToSession($request, false);

        // save "no-consent" to db
        $this->persistConsentSettings(false);

        $headerBag = new ResponseHeaderBag();
        $headerBag->setCookie($consentCookie);

        return $headerBag;
    }

    public function saveConsentSettings(mixed $getData, Request $request)
    {

    }
}