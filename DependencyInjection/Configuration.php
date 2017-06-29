<?php

namespace beabys\PaybookBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('beabys_paybook');

        $rootNode
            ->children()
                ->scalarNode('paybook_id_user')->isRequired()->end()
                ->scalarNode('paybook_api_key')->isRequired()->end()
            ->end()
        ;


        return $treeBuilder;
    }
}
