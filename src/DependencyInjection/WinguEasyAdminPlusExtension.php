<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class WinguEasyAdminPlusExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container): void
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        $container->setParameter('wingu_easy_admin_plus.logo', $config['logo']);
        $container->setParameter('wingu_easy_admin_plus.title', $config['title']);
    }
}
