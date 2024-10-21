<?php

declare(strict_types=1);


use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\HttpFoundation\Cookie;

function addCookie(string $key, string $name): ArrayNodeDefinition
{
    $builder = new TreeBuilder($key);
    $node = $builder->getRootNode();

    // @formatter:off
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
        ->defaultValue(Cookie::SAMESITE_LAX)
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
    // @formatter:on

    return $node;
}