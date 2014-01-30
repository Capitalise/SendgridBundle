<?php

namespace Savch\SendgridBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Andriy Savchenko andriy.savchenko@gmail.com
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('savch_sendgrid');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('api_user')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('logging')->defaultValue('%kernel.debug%')->end()
            ->end();

        return $treeBuilder;
    }
}
