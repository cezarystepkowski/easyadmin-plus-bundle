<?php

declare(strict_types = 1);

namespace Wingu\EasyAdminPlusBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wingu\EasyAdminPlusBundle\DependencyInjection\CompilerPass\EasyAdminOverwrite;

class WinguEasyAdminPlusBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new EasyAdminOverwrite());
    }
}
