<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\tests\Controller;

use huppys\CookieConsentBundle\Controller\CookieConsentController;
use huppys\CookieConsentBundle\Form\ConsentDetailedType;
use huppys\CookieConsentBundle\Form\ConsentSimpleType;
use huppys\CookieConsentBundle\Service\CookieConsentService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class CookieConsentControllerTest extends TestCase
{
    private MockObject $templating;
    private MockObject $formFactory;
    private MockObject $translator;
    private MockObject $cookieConsentService;
    private MockObject $requestStack;
    private MockObject $logger;
    private Request $request;

    private CookieConsentController $cookieConsentController;
    private $locale = 'en';

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->templating = $this->createMock(Environment::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->translator = $this->createMock(Translator::class);
        $router = $this->createMock(RouterInterface::class);
        $this->cookieConsentService = $this->createMock(CookieConsentService::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->logger = $this->createMock(Logger::class);

        $this->request = new Request();
        $this->request->setLocale($this->locale);

        $this->requestStack
            ->expects($this->any())
            ->method('getCurrentRequest')
            ->willReturn($this->request);

        $this->cookieConsentController = new CookieConsentController(
            $this->templating,
            $this->formFactory,
            $router,
            $this->translator,
            null,
            null,
            $this->cookieConsentService,
            'top',
            $this->requestStack,
            $this->logger
        );
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function shouldReturnResponseAfterRender(): void
    {
        $this->expectFormsAreRendered();

        $this->givenTemplateRendersTest();

        $response = $this->cookieConsentController->view($this->request);

        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @throws Exception
     */
    #[Test]
    public function shouldShowConsentFormIfCookieNotSet(): void
    {
        $this->expectFormsAreRendered();

        $this->givenCookieConsentNotSetByUser();

        $response = $this->cookieConsentController->viewIfNoConsent();

        $this->assertInstanceOf(Response::class, $response);
    }

    #[Test]
    public function shouldShowIfCookieConsentNotSetWithLocale(): void
    {
        $this->givenCookieConsentNotSetByUser();

        $this->givenTemplateRendersTest();

        $this->translator
            ->expects($this->once())
            ->method('setLocale')
            ->with($this->locale);

        $response = $this->cookieConsentController->viewIfNoConsent();

        $this->assertInstanceOf(Response::class, $response);
    }

    #[Test]
    public function shouldShowIfCookieConsentNotSetWithCookieConsentSet(): void
    {
        $this->givenCookieConsentSetByUser();

        $this->formFactory
            ->expects($this->never())
            ->method('create')
            ->with(ConsentSimpleType::class);

        $this->templating
            ->expects($this->never())
            ->method('render');

        $response = $this->cookieConsentController->viewIfNoConsent();

        $this->assertInstanceOf(Response::class, $response);
    }

    private function givenCookieConsentSetByUser(): void
    {
        $this->cookieConsentService
            ->expects($this->any())
            ->method('isCookieConsentFormSubmittedByUser')
            ->with($this->request)
            ->willReturn(true);
    }

    private function givenCookieConsentNotSetByUser(): void
    {
        $this->cookieConsentService
            ->expects($this->any())
            ->method('isCookieConsentFormSubmittedByUser')
            ->with($this->request)
            ->willReturn(false);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function expectFormsAreRendered(): void
    {
        $this->formFactory
            ->expects($this->exactly(2))
            ->method('createBuilder')
            ->with($this->logicalXor(ConsentDetailedType::class, ConsentSimpleType::class))
            ->willReturn($this->createMock(FormBuilderInterface::class));
    }

    /**
     * @return void
     */
    public function givenTemplateRendersTest(): void
    {
        $this->templating
            ->expects($this->once())
            ->method('render')
            ->willReturn('test');
    }
}
