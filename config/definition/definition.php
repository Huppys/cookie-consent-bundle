<?php

declare(strict_types=1);

use huppys\CookieConsentBundle\Enum\ConsentBannerPosition;
use huppys\CookieConsentBundle\Enum\CookieCategory;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

require_once __DIR__ . '/utils/cookie.php';
require_once __DIR__ . '/utils/cookie-settings.php';


return static function (DefinitionConfigurator $definition) {
    // @formatter:off
    $definition
        ->rootNode()
            ->children()
                ->append(addCookieSettingsNode())
                ->variableNode('consent_categories')
                    ->defaultValue([CookieCategory::ANALYTICS, CookieCategory::TRACKING, CookieCategory::MARKETING, CookieCategory::SOCIAL_MEDIA])
                    ->info('Set the categories of consent that should be used')
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
