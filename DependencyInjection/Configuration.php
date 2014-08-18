<?php

namespace Yan\Bundle\SitePlatformBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('site_platform');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->scalarNode('database_host')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('database_user')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('database_password')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('database_name')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
