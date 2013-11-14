<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */
namespace Bzl\Bundle\ZfViewBundle;

use Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass\AsseticPass;
use Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass\EventManagerListenersPass;
use Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass\TemplatingPass;
use Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass\ViewHelpersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ZfViewBundle
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bzl\Bundle\ZfViewBundle
 */
class ZfViewBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TemplatingPass());
        $container->addCompilerPass(new EventManagerListenersPass());
        $container->addCompilerPass(new ViewHelpersPass());
    }
}
