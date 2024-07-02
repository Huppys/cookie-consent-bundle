<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle;

use huppys\CookieConsentBundle\Controller\CookieConsentController;
use huppys\CookieConsentBundle\Cookie\CookieChecker;
use huppys\CookieConsentBundle\Cookie\CookieHandler;
use huppys\CookieConsentBundle\EventSubscriber\CookieConsentFormSubscriber;
use huppys\CookieConsentBundle\Repository\CookieConsentRepository;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class CookieConsentBundle extends AbstractBundle
{
    protected string $extensionAlias = 'cookie_consent';

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition/definition.php', type: 'php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $services = $container->services();

        $services->load('huppys\\CookieConsentBundle\\', '../src/')
            ->exclude('../src/{DependencyInjection,Entity,Enum,Kernel/*.php}');

        $services->defaults()
            ->autowire()
            ->autoconfigure()
            ->private();

        $services->defaults()
            ->bind('$position', $config['position'])
            ->bind('string $formAction', $config['form_action'])
            ->bind('string $readMoreRoute', $config['read_more_route']);

        // the controller has to be public
        $services->set(CookieConsentController::class)->public()->autowire(true);

        // configure manually wired constructor arguments for private services
        $services->set(CookieChecker::class)->args([service('request_stack')]);
        $services->set(CookieHandler::class)->args([$config['cookie_settings']]);
        $services->set(CookieConsentFormSubscriber::class)->args([$config['persist_consent']]);
        $services->set(CookieConsentRepository::class)->args([service('doctrine')]);


        //        Register pre-compile classes here?
        //        $this->addAnnotatedClassesToCompile([
        //            // you can define the fully qualified class names...
        //            'Acme\\BlogBundle\\Controller\\AuthorController',
        //            // ... but glob patterns are also supported:
        //            'Acme\\BlogBundle\\Form\\**',
        //
        //            // ...
        //        ]);
    }
}
