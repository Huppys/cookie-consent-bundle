<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\EventSubscriber;

use huppys\CookieConsentBundle\Cookie\CookieHandler;
use huppys\CookieConsentBundle\Cookie\CookieLogger;
use huppys\CookieConsentBundle\Enum\CookieNameEnum;
use huppys\CookieConsentBundle\Form\CookieConsentType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CookieConsentFormSubscriber implements EventSubscriberInterface
{
    private FormFactoryInterface $formFactory;
    private CookieLogger $cookieLogger;
    private CookieHandler $cookieHandler;
    private bool $useLogger;

    public function __construct(FormFactoryInterface $formFactory, CookieLogger $cookieLogger, CookieHandler $cookieHandler, bool $useLogger)
    {
        $this->formFactory   = $formFactory;
        $this->cookieLogger  = $cookieLogger;
        $this->cookieHandler = $cookieHandler;
        $this->useLogger     = $useLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
           ResponseEvent::class => ['onResponse'],
        ];
    }

    /**
     * Checks if form has been submitted and saves users preferences in cookies by calling the CookieHandler.
     */
    public function onResponse(ResponseEvent $event): void
    {
        $request  = $event->getRequest();
        $response = $event->getResponse();

        $form = $this->createCookieConsentForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleFormSubmit($form->getData(), $request, $response);
        }
    }

    /**
     * Handle form submit.
     */
    protected function handleFormSubmit(array $categories, Request $request, Response $response): void
    {
        $cookieConsentKey = $this->getCookieConsentKey($request);

        $this->cookieHandler->save($categories, $cookieConsentKey, $response);

        if ($this->useLogger) {
            $this->cookieLogger->log($categories, $cookieConsentKey);
        }
    }

    /**
     *  Return existing key from cookies or create new one.
     */
    protected function getCookieConsentKey(Request $request): string
    {
        // TODO: Generate random bytes or use UUID
        return $request->cookies->get(CookieNameEnum::COOKIE_CONSENT_KEY_NAME) ?? uniqid();
    }

    /**
     * Create cookie consent form.
     */
    protected function createCookieConsentForm(): FormInterface
    {
        return $this->formFactory->create(CookieConsentType::class);
    }
}
