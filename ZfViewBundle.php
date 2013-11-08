<?php

namespace Bzl\Bundle\ZfViewBundle;

use Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass\EventManagerListenersPass;
use Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass\ViewHelpersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ZfViewBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new EventManagerListenersPass());
        $container->addCompilerPass(new ViewHelpersPass());
    }
}
