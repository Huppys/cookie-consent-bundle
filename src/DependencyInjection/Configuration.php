<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\DependencyInjection;

use huppys\CookieConsentBundle\Enum\ConsentBannerPosition;
use huppys\CookieConsentBundle\Enum\CookieCategory;
use huppys\CookieConsentBundle\Enum\CookieName;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('cookie_consent');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->append($this->addCookieSettingsNode())
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
                    ->defaultNull()
                ->end()
                ->scalarNode('read_more_route')
                    ->defaultNull()
                ->end()
                ->booleanNode('csrf_protection')
                    ->defaultTrue()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function addCookieSettingsNode(): ArrayNodeDefinition
    {
        $builder = new TreeBuilder('cookie_settings');
        $node = $builder->getRootNode();

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
                        ->append($this->addCookie('consent_cookie', CookieName::COOKIE_CONSENT_NAME))
                        ->append($this->addCookie('consent_key_cookie', CookieName::COOKIE_CONSENT_KEY_NAME))
                        ->append($this->addCookie('consent_categories_cookie', CookieName::COOKIE_CATEGORY_NAME_PREFIX))
                    ->end()
                ->end()
            ->end();
        return $node;
    }

    private function addCookie(string $key, string $name): ArrayNodeDefinition
    {
        $builder = new TreeBuilder($key);
        $node = $builder->getRootNode();

        $node
            ->addDefaultsIfNotSet()
            ->canBeDisabled()
            ->children()
                ->variableNode('name')
                    ->info('Set the name of the cookie')
                    ->defaultValue($name)
                ->end()
                ->booleanNode('http_only')
                    ->info('Set if the cookie should be accessible only through the HTTP protocol')
                    ->defaultTrue()
                ->end()
                ->booleanNode('secure')
                    ->info('Set if the cookie should only be transmitted over a secure HTTPS connection from the client')
                    ->defaultTrue()
                ->end()
                ->enumNode('same_site')
                    ->info('Set the value for the SameSite attribute of the cookie')
                    ->values(['lax', 'strict'])
                    ->defaultValue('lax')
                ->end()
                ->variableNode('domain')
                    ->info('Set the value for the Domain attribute of the cookie')
                    ->defaultNull()
                ->end()
                ->scalarNode('expires')
                    ->info('Set the value for the Expires attribute of the cookie')
                    ->defaultValue('P180D')
                ->end()
            ->end();

        return $node;
    }
}