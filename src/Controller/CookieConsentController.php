<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Controller;

use huppys\CookieConsentBundle\Cookie\CookieChecker;
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
        private readonly CookieChecker        $cookieChecker,
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
        $request = $this->requestStack->getCurrentRequest();

        $this->setLocale($request);

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
        $request = $this->requestStack->getCurrentRequest();

        if ($request->getMethod() != Request::METHOD_POST) {
            throw new MethodNotAllowedException([Request::METHOD_POST]);
        }

        // TODO: Add validation via doctrine validators: https://symfony.com/doc/current/doctrine.html#validating-objects
        $form = $this->createSimpleConsentForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($rejectAllButton = $form->get(FormSubmitName::REJECT_ALL)) {
                $rejectAll = $rejectAllButton->isClicked();

                if ($rejectAll) {
                    // tell consent manager service to reject all cookies, sets consent cookie to false
                    $responseHeaders = $this->cookieConsentService->rejectAllCookies($request);

                    return new JsonResponse('ok', Response::HTTP_CREATED, headers: ['set-cookie' => $responseHeaders->getCookies()]);
                }
            }

            if ($acceptAllButton = $form->get(FormSubmitName::ACCEPT_ALL)) {
                $acceptAll = $acceptAllButton->isClicked();

                if ($acceptAll) {
                    // tell consent manager service to set cookie values accordingly
                    $responseHeaders = $this->cookieConsentService->acceptAllCookies($request);

                    return new JsonResponse('ok', Response::HTTP_CREATED, headers: ['set-cookie' => $responseHeaders->getCookies()]);
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
    #[Route('/cookie-consent/show-if-not-set', name: 'cookie_consent.show_if_cookie_consent_not_set')]
    public function showIfCookieConsentNotSet(Request $request): Response
    {
        if ($this->cookieChecker->isCookieConsentOptionSetByUser() === false) {
            return $this->view($request);
        }

        return new Response();
    }

    /**
     * Set locale if available as GET parameter.
     */
    private function setLocale(Request $request): void
    {
        $locale = $request->get('locale');
        if (empty($locale) === false) {
            $this->translator->setLocale($locale);
            $request->setLocale($locale);
        }
    }
}
