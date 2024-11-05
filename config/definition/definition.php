<?php

declare(strict_types=1);

use huppys\CookieConsentBundle\Enum\ConsentBannerPosition;
use huppys\CookieConsentBundle\Enum\CookieName;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

require_once __DIR__ . '/utils/cookie.php';


return static function (DefinitionConfigurator $definition) {
    // @formatter:off
    $definition
        ->rootNode()
            ->children()
                ->arrayNode('consent_configuration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->append(consentCookie('consent_cookie', CookieName::COOKIE_CONSENT_NAME))
                        ->arrayNode('consent_categories')
                            ->arrayPrototype() // freely define your category name like 'functional' or 'social_media'
                                ->scalarPrototype() // freely define the cookie's key like 'twitter' or 'hotjar' to define it's
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->enumNode('position')
                    ->defaultValue(ConsentBannerPosition::POSITION_DIALOG)
                    ->values(ConsentBannerPosition::getAvailablePositions())
                ->end()
                ->booleanNode('persist_consent')
                    ->defaultTrue()
                ->end()
                ->scalarNode('form_action')
                    ->defaultValue('cookie-consent.update')
                ->end()
                ->scalarNode('read_more_route')
                    ->defaultNull()
                ->end()
                ->booleanNode('csrf_protection')
                   ->defaultTrue()
                ->end()
            ->end();
    // @formatter:on
};
