<?php

declare(strict_types=1);

namespace Wingu\EasyAdminPlusBundle\DependencyInjection;

use FOS\UserBundle\Controller\SecurityController as FOSSecurityController;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Wingu\EasyAdminPlusBundle\Controller\SecurityController as WinguSecurityController;

class WinguEasyAdminPlusExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $config, ContainerBuilder $container): void
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        if (!\class_exists(FOSSecurityController::class)) {
            $container->removeDefinition(WinguSecurityController::class);
        }
    }
}
