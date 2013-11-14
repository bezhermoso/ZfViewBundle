<?php

namespace Bzl\Bundle\ZfViewBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZfViewExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('engine.yml');
        $loader->load('assetic.yml');
        $loader->load('view_helpers.yml');


        if ($container->getParameter('kernel.debug')) {

            $loader->load('debug.yml');
            $container->setDefinition('bzl.zf_view.engine', $container->findDefinition('debug.bzl.zf_view.engine'));
            $container->setAlias('debug.bzl.zf_view.engine', 'bzl.zf_view.engine');

        }


    }

    public function getAlias()
    {
        return 'zf_view';
    }
}
