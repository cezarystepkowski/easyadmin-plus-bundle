<?php

declare(strict_types=1);

namespace Wingu\EasyAdminPlusBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wingu\EasyAdminPlusBundle\Form\Type\EasyAdminAutocompleteType;

final class EasyAdminOverwrite implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container) : void
    {
        $container
            ->getDefinition('easyadmin.form.type.autocomplete')
            ->setClass(EasyAdminAutocompleteType::class);
    }
}
