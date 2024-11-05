<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Controller;

use Exception;
use huppys\CookieConsentBundle\Enum\FormSubmitName;
use huppys\CookieConsentBundle\Form\ConsentDetailedType;
use huppys\CookieConsentBundle\Form\ConsentSimpleType;
use huppys\CookieConsentBundle\Service\CookieConsentService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsController]
class CookieConsentController
{
    public function __construct(
        private readonly Environment          $twigEnvironment,
        private readonly FormFactoryInterface $formFactory,
        private readonly RouterInterface      $router,
        private readonly LocaleAwareInterface $translator,
        private readonly string|null          $formAction,
        private readonly string|null          $readMoreRoute,
        private readonly CookieConsentService $cookieConsentService,
        private readonly string               $position,
        private readonly RequestStack         $requestStack,
        private readonly LoggerInterface      $logger
    )
    {
    }

    /**
     * Show cookie consent.
     */
    #[Route('/cookie-consent/view', name: 'cookie_consent.view')]
    public function view(): Response
    {
        $this->setLocale($this->getCurrentRequest());

        try {
            $response = new Response(
                $this->twigEnvironment->render('@CookieConsent/cookie_consent.html.twig', [
                    'simple_form' => $this->createSimpleConsentForm()->createView(),
                    'detailed_form' => $this->createDetailedConsentForm()->createView(),
                    'position' => $this->position,
                    'read_more_route' => $this->readMoreRoute,
                ])
            );

            // Cache in ESI should not be shared
            $response->setPrivate();
            $response->setMaxAge(0);

            return $response;
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/cookie-consent/update', name: 'cookie-consent.update')]
    public function update(): Response
    {
        $request = $this->getCurrentRequest();

        if ($request->getMethod() != Request::METHOD_POST) {
            throw new MethodNotAllowedException([Request::METHOD_POST]);
        }

        // TODO: Add validation via doctrine validators: https://symfony.com/doc/current/doctrine.html#validating-objects
        $form = $this->getForm($request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($rejectAllButton = $form->get(FormSubmitName::REJECT_ALL)) {
                $rejectAll = $rejectAllButton->isClicked();

                if ($rejectAll) {
                    try {
                        // tell consent manager service to reject all cookies, sets consent cookie to false
                        $responseHeaders = $this->cookieConsentService->rejectAllCookies($request);

                        return new JsonResponse('ok', Response::HTTP_CREATED, headers: ['set-cookie' => $responseHeaders->getCookies()]);
                    } catch (Exception $exception) {
                        // TODO: handle exception
                    }

                }
            }

            if ($acceptAllButton = $form->get(FormSubmitName::ACCEPT_ALL)) {
                $acceptAll = $acceptAllButton->isClicked();

                if ($acceptAll) {
                    try {
                        // tell consent manager service to set cookie values accordingly
                        $responseHeaders = $this->cookieConsentService->acceptAllCookies($form->getData(), $request);

                        return new JsonResponse('ok', Response::HTTP_CREATED, headers: ['set-cookie' => $responseHeaders->getCookies()]);
                    } catch (Exception $exception) {
                        // TODO: handle exception
                    }
                }
            }

            $this->cookieConsentService->saveConsentSettings($form->getData(), $request);

            return new JsonResponse('ok', Response::HTTP_CREATED);


        } else if ($form->isSubmitted() && $form->getClickedButton() == null) {
            $this->logger->error('Invalid form passed to consent manager update');
            return new JsonResponse('error', status: Response::HTTP_BAD_REQUEST);
        }

        $this->logger->error('Error while updating cookies via consent manager');
        return new JsonResponse('error', status: Response::HTTP_BAD_REQUEST);
    }

    /**
     * Create cookie consent form.
     */
    private function createSimpleConsentForm(): FormInterface
    {
        $formBuilder = $this->formFactory->createBuilder(ConsentSimpleType::class);

        if ($this->formAction != null) {
            $formBuilder->setAction($this->router->generate($this->formAction));
        }

        return $formBuilder->getForm();
    }

    private function createDetailedConsentForm(): FormInterface
    {
        $formBuilder = $this->formFactory->createBuilder(ConsentDetailedType::class);

        if ($this->formAction != null) {
            $formBuilder->setAction($this->router->generate($this->formAction));
        }

        return $formBuilder->getForm();
    }

    /**
     * Show cookie consent.
     */
    #[Route('/cookie-consent/view-if-not-set', name: 'cookie_consent.show_if_cookie_consent_not_set')]
    public function showIfCookieConsentNotSet(): Response
    {
        if ($this->cookieConsentService->isCookieConsentOptionSetByUser($this->getCurrentRequest()) === false) {
            return $this->view();
        }

        return new Response();
    }

    /**
     * Set locale if available as GET parameter.
     */
    private function setLocale(Request $request): void
    {
        $locale = $request->getLocale();
        if (empty($locale) === false) {
            $this->translator->setLocale($locale);
            $request->setLocale($locale);
        }
    }

    private function getForm(Request $request): ?FormInterface
    {
        if ($request->get("consent_simple") != null) {
            return $this->createSimpleConsentForm();
        } else if ($request->get("consent_detailed") != null) {
            return $this->createDetailedConsentForm();
        }

        return null;
    }

    /**
     * @return Request|null
     */
    public function getCurrentRequest(): ?Request
    {
        return $this->requestStack->getCurrentRequest();
    }
}
