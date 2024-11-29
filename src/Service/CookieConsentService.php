<?php

namespace huppys\CookieConsentBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use huppys\CookieConsentBundle\Enum\ConsentType;
use huppys\CookieConsentBundle\Enum\CookieName;
use huppys\CookieConsentBundle\Form\ConsentCategoryTypeModel;
use huppys\CookieConsentBundle\Form\ConsentDetailedTypeModel;
use huppys\CookieConsentBundle\Form\ConsentVendorTypeModel;
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
        $consentSettingsFromSession = $request->getSession()->get('consent-settings');

        return $request->cookies->get($category) === 'true';
    }

    /**
     * Check if user gave consent for vendor in category
     * @param string $vendor
     * @param string $category
     * @param Request $request
     * @return bool
     */
    public function isVendorAllowedByUser(string $vendor, string $category, Request $request): bool
    {

    }

    /**
     * Check if cookie consent has already been saved.
     * @return bool
     */
    public function isCookieConsentFormSubmittedByUser(Request $request): bool
    {
        $consentSettingsFromSession = $request->getSession()->get('consent-settings');

        return $request->cookies->has(CookieName::COOKIE_CONSENT_NAME) && $consentSettingsFromSession != null;
    }

    /**
     * @param Request $request
     * @return ResponseHeaderBag
     * @throws InvalidArgumentException
     */
    public function rejectAllCookies(Request $request): ResponseHeaderBag
    {
        // always set value to false as the user didn't give the consent to use more cookies than necessary but we use the 'consent' cookie to hide the UI
        $consentCookie = CookieConfigMapper::mapToCookie($this->consentConfiguration['consent_cookie'], ConsentType::NO_CONSENT);

        if ($consentCookie == null) {
            throw new InvalidArgumentException("Cookie configuration can't be mapped to a Cookie");
        }

        // save "no-consent" to session
        $this->saveConsentSettingsToSession($request, ConsentType::NO_CONSENT);

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

    public function saveConsentSettings(mixed $formData, Request $request): ResponseHeaderBag
    {
        // if full consent was given, return
        if ($this->formDataEqualsFullConsent($formData)) {
            return $this->acceptAllCookies($formData, $request);
        }

        // always set value to true as the user did give the consent to at least some of the cookies
        $consentCookie = CookieConfigMapper::mapToCookie($this->consentConfiguration['consent_cookie'], ConsentType::CUSTOM_CONSENT);

        if ($consentCookie == null) {
            throw new InvalidArgumentException("Cookie configuration can't be mapped to a Cookie");
        }

        // save "no-consent" to session
        $this->saveConsentSettingsToSession($request, $formData);

        // save "no-consent" to db
        $this->persistConsentSettings(false);

        $headerBag = new ResponseHeaderBag();
        $headerBag->setCookie($consentCookie);

        return $headerBag;
    }

    private function formDataEqualsFullConsent(ConsentDetailedTypeModel $formData): bool
    {
        /** @var ArrayCollection $categories */
        $categories = $formData->getCategories();

        if ($categories->isEmpty()) {
            return false;
        }

        $categoryConsentDenied = $categories->findFirst(function (int $key, ConsentCategoryTypeModel $category) {

            if ($category->getConsentGiven() === false) {
                return true;
            }

            /** @var ArrayCollection $vendors */
            $vendors = $category->getVendors();

            $vendorConsentDenied = $vendors->findFirst(function (int $key, ConsentVendorTypeModel $value): bool {
                return $value->getConsentGiven() === false;
            });

            return $vendorConsentDenied != null;
        });

        return $categoryConsentDenied === null;
    }

    /**
     * @param mixed $formData
     * @param Request $request
     * @return ResponseHeaderBag
     * @throws InvalidArgumentException
     */
    public function acceptAllCookies(mixed $formData, Request $request): ResponseHeaderBag
    {
        // always set value to true as the user did give the consent to use all cookies
        $consentCookie = CookieConfigMapper::mapToCookie($this->consentConfiguration['consent_cookie'], ConsentType::FULL_CONSENT);

        if ($consentCookie == null) {
            throw new InvalidArgumentException("Cookie configuration can't be mapped to a Cookie");
        }

        // save "no-consent" to session
        $this->saveConsentSettingsToSession($request, ConsentType::FULL_CONSENT);

        // save "no-consent" to db
        $this->persistConsentSettings(false);

        $headerBag = new ResponseHeaderBag();
        $headerBag->setCookie($consentCookie);

        return $headerBag;
    }

    public function createDetailedForm(): ConsentDetailedTypeModel
    {
        $consentConfig = $this->consentConfiguration;

        $formModel = new ConsentDetailedTypeModel();

        foreach ($consentConfig['consent_categories'] as $categoryKey => $category) {

            $consentCategory = new ConsentCategoryTypeModel();
            $consentCategory->setName($categoryKey);
            $consentCategory->setConsentGiven(false);

            foreach ($category as $vendorKey => $vendor) {
                $consentCookie = new ConsentVendorTypeModel();

                // explicitly set fields from formData
                $consentCookie->setName($vendor);
                $consentCookie->setConsentGiven(false);
                $consentCookie->setDescriptionKey($vendor);

                $consentCategory->getVendors()->add($consentCookie);
            }

            $formModel->getCategories()->add($consentCategory);
        }

        return $formModel;
    }
}
