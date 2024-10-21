<?php

namespace huppys\CookieConsentBundle\Service;

use huppys\CookieConsentBundle\Mapper\CookieConfigMapper;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CookieConsentService
{


    public function __construct(private readonly array $cookieSettings, private readonly bool $persistConsent)
    {
    }

    public function rejectAllCookies(Request $request): ResponseHeaderBag
    {
        // get CookieBundle config to gain consent cookie configuration
        $consentCookieConfiguration = $this->cookieSettings['cookies']['consent_cookie'];

        // always set value to false as the user didn't give the consent to use more cookies than necessary but we use the 'consent' cookie to hide the UI
        $consentCookie = CookieConfigMapper::mapToCookie($consentCookieConfiguration, 'false', $this->cookieSettings['name_prefix']);

        if ($consentCookie == null) {
            throw new InvalidArgumentException("Cookie configuration can't be mapped to a Cookie");
        }

        // save "no-consent" to session
        $this->saveConsentSettingsToSession($request, false);

        // save "no-consent" to db
        $this->persistConsentSettings();

        $headerBag = new ResponseHeaderBag();
        $headerBag->setCookie($consentCookie);

        return $headerBag;
    }

    public function acceptAllCookies(mixed $getData, Request $request): ResponseHeaderBag
    {
        // get CookieBundle config to gain consent cookie configuration
        $cookiesConfiguration = $this->cookieSettings['cookies'];

        // always set value to false as the user didn't give the consent to use more cookies than necessary but we use the 'consent' cookie to hide the UI
        $consentCookie = CookieConfigMapper::mapToCookie($consentCookieConfiguration, 'false', $this->cookieSettings['name_prefix']);

        if ($consentCookie == null) {
            throw new InvalidArgumentException("Cookie configuration can't be mapped to a Cookie");
        }

        // save "no-consent" to session
        $this->saveConsentSettingsToSession($request, $getData);

        // save "no-consent" to db
        $this->persistConsentSettings();

        $headerBag = new ResponseHeaderBag();
        $headerBag->setCookie($consentCookie);

        return $headerBag;
    }

    public function saveConsentSettings(mixed $getData, Request $request)
    {

    }

    private function persistConsentSettings(): void
    {
        if ($this->persistConsent) {
            // TODO: implement method
//        $this->entityManager->persist($cookieLog);

//        $this->entityManager->flush();

            // persist consent log object
        }
    }

    private function saveConsentSettingsToSession(Request $request, mixed $value): void
    {
        $session = $request->getSession();

        // save consent settings in session
        $session->set('consent-settings', $value);
    }
}