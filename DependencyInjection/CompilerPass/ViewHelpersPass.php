<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ViewHelpersPass
 *
 * Registers tagged view helpers.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass
 */
class ViewHelpersPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.     *
     * @param ContainerBuilder $container
     * @throws \RuntimeException
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bzl.zf_view.helper_manager')) {
            return;
        }

        $aggregateManager = $container->getDefinition('bzl.zf_view.helper_manager');

        $pluginManagers = $container->findTaggedServiceIds('zf_view.plugin_manager');

        foreach ($pluginManagers as $id => $tags) {

            foreach ($tags as $attributes) {
                $attributes = $this->resolvePluginManagerAttributes($attributes);
                $aggregateManager->addMethodCall(
                                    'addPluginManager',
                                    array(new Reference($id), $attributes['priority']));
            }

        }

        $helperManager = $container->getDefinition('bzl.zf_view.helper_manager.original');
        $viewHelpers = $container->findTaggedServiceIds('zend.view_helper');

        foreach ($viewHelpers as $id => $tags) {
            foreach ($tags as $attributes) {

                $attributes = $this->resolveViewHelperAttributes($attributes);

                if(null == $attributes['alias']) {
                    throw new \RuntimeException(sprintf('An alias attribute must be specified in all view helpers. None given for service "%s".', $id));
                }

                $alias = $attributes['alias'];

                switch ($attributes['type']) {
                    default:
                        $helperManager->addMethodCall('setService', array($alias, new Reference($id)));
                        break;
                }
            }
        }
    }

    public function resolveViewHelperAttributes(array $attributes)
    {
        return array_merge(array(
            'type' => 'service',
            'alias' => null,
        ), $attributes);
    }

    public function resolvePluginManagerAttributes(array $attributes)
    {
        return array_merge(array(
            'priority' => 1,
        ), $attributes);
    }
}