<?php

declare(strict_types=1);

use huppys\CookieConsentBundle\Enum\CookieName;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

function addCookieSettingsNode(): ArrayNodeDefinition
{
    $builder = new TreeBuilder('cookie_settings');
    $node = $builder->getRootNode();

    // @formatter:off
    $node
        ->addDefaultsIfNotSet()
        ->children()
            ->scalarNode('name_prefix')
            ->defaultValue('')
            ->info('Prefix the cookie names, if necessary')
        ->end()
        ->arrayNode('cookies')
            ->addDefaultsIfNotSet()
            ->children()
                ->append(addCookie('consent_cookie', CookieName::COOKIE_CONSENT_NAME))
                ->append(addCookie('consent_key_cookie', CookieName::COOKIE_CONSENT_KEY_NAME))
                ->end()
            ->end()
        ->end();
    // @formatter:on

    return $node;
}