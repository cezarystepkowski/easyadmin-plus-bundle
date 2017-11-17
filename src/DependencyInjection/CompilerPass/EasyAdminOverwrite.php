<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wingu\EasyAdminPlusBundle\Form\Type\EasyAdminAutocompleteType;
use Wingu\EasyAdminPlusBundle\Twig\Extension\EasyAdminTwigExtension;

final class EasyAdminOverwrite implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $container
            ->getDefinition('easyadmin.twig.extension')
            ->setClass(EasyAdminTwigExtension::class);

        $container
            ->getDefinition('easyadmin.form.type.autocomplete')
            ->setClass(EasyAdminAutocompleteType::class);
    }
}
