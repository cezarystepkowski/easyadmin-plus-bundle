<?php

declare(strict_types=1);

namespace Wingu\EasyAdminPlusBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder() : TreeBuilder
    {
        return new TreeBuilder('wingu_easy_admin_plus');
    }
}
