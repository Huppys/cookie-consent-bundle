<?php

namespace huppys\CookieConsentBundle\tests\Fixtures\App;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use huppys\CookieConsentBundle\CookieConsentBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new DoctrineBundle(),
            new CookieConsentBundle()
        ];
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->setParameter('kernel.project_dir', __DIR__);

        $container->setParameter('kernel.secret', 'thisIsASecret');

        $container->loadFromExtension('framework', [
            'test' => true,
        ]);

        $container->loadFromExtension('doctrine', [
            'dbal' => [
                'driver' => 'pdo_sqlite'
            ],
            'orm' => [
                // ASK: Why is 'auto_mapping' => true required to successfully run CookieConsentBundleServicesTest#shouldProvideController() ?
                'auto_mapping' => true
            ]
        ]);

        $container->loadFromExtension('cookie_consent', [
            'cookie_settings' => [
            ],
            'consent_categories' => ['social_media', 'analytics', 'marketing'],
            'position' => 'top',
        ]);
    }
}