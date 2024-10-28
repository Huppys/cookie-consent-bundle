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

    /**
     * @param Request $request
     * @return ResponseHeaderBag
     * @throws InvalidArgumentException
     */
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
        $this->persistConsentSettings(false);

        $headerBag = new ResponseHeaderBag();
        $headerBag->setCookie($consentCookie);

        return $headerBag;
    }

    /**
     * @param mixed $getData
     * @param Request $request
     * @return ResponseHeaderBag
     * @throws InvalidArgumentException
     */
    public function acceptAllCookies(mixed $getData, Request $request): ResponseHeaderBag
    {
        // get CookieBundle config to gain consent cookie configuration
        $cookiesConfiguration = $this->cookieSettings['cookies'];

        $headerBag = new ResponseHeaderBag();

        foreach ($cookiesConfiguration as $configuration) {

            // always set value to true as the user gave the consent to cookies
            $consentCookie = CookieConfigMapper::mapToCookie($configuration, 'true', $this->cookieSettings['name_prefix']);

            if ($consentCookie == null) {
                throw new InvalidArgumentException("Cookie configuration can't be mapped to a Cookie");
            }

            $headerBag->setCookie($consentCookie);
        }

        // save "no-consent" to session
        $this->saveConsentSettingsToSession($request, $headerBag->getCookies());

        // save "no-consent" to db
        $this->persistConsentSettings($headerBag->getCookies());

        return $headerBag;
    }

    public function saveConsentSettings(mixed $getData, Request $request)
    {

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

private
function saveConsentSettingsToSession(Request $request, mixed $value): void
{
    $session = $request->getSession();

    // save consent settings in session
    $session->set('consent-settings', $value);
}
}