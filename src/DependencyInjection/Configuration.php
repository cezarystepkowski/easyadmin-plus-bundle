<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wingu_easy_admin_plus');

        $rootNode
            ->children()
                ->scalarNode('title')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('logo')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('advanced_search_form_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->defaultValue('form-horizontal')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
