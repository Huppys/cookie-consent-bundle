<?php

namespace huppys\CookieConsentBundle\tests\Fixtures\App;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use huppys\CookieConsentBundle\CookieConsentBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\MakerBundle\MakerBundle;
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
            new MakerBundle(),
            new DoctrineMigrationsBundle(),
            new CookieConsentBundle()
        ];
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
//        $container->setParameter('kernel.project_dir', __DIR__);

        $container->setParameter('kernel.secret', 'thisIsASecret');

        $container->loadFromExtension('framework', [
            'test' => true,
            'default_locale' => 'en',
            'translator' =>
                ['default_path' => '%kernel.project_dir%/translations']
        ]);

        $container->loadFromExtension('doctrine', [
            'dbal' => [
                'driver' => 'pdo_sqlite',
                'url' => 'sqlite:///%kernel.project_dir%/data/test.db',
                'path' => '%kernel.project_dir%/data/test.db',
            ],
            'orm' => [
                // ASK: Why is 'auto_mapping' => true required to successfully run CookieConsentBundleServicesTest#shouldProvideController() ?
                'auto_mapping' => true
            ]
        ]);


        $container->loadFromExtension('doctrine_migrations', [
            'migrations_paths' => [
                "huppys\\CookieConsentBundle" => "%kernel.project_dir%/migrations"
            ]
        ]);

        $container->loadFromExtension('maker', [
            'root_namespace' => "huppys\\CookieConsentBundle"
        ]);

        $container->loadFromExtension('cookie_consent', [
            'cookie_settings' => [
            ],
            'consent_categories' => ['social_media', 'analytics', 'marketing'],
            'position' => 'top',
        ]);
    }
}