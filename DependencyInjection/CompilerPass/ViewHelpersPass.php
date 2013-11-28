<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Bez\ZfViewBundle\DependencyInjection\CompilerPass;

use Bez\ZfViewBundle\View\HelpersInContainer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ViewHelpersPass
 *
 * Registers tagged view helpers.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bez\ZfViewBundle\DependencyInjection\CompilerPass
 */
class ViewHelpersPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.     *
     * @param ContainerBuilder $container
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bez.zf_view.helper_manager')) {
            return;
        }

        $aggregateManager = $container->getDefinition('bez.zf_view.helper_manager');

        $pluginManagers = $container->findTaggedServiceIds('zf_view.plugin_manager');

        foreach ($pluginManagers as $id => $tags) {

            foreach ($tags as $attributes) {
                $attributes = $this->resolvePluginManagerAttributes($attributes);
                $aggregateManager->addMethodCall(
                                    'addPluginManager',
                                    array(new Reference($id), $attributes['priority']));
            }

        }

        $helperManager = $container->getDefinition('bez.zf_view.helper_manager.original');
        $viewHelpers = $container->findTaggedServiceIds('zend.view_helper');

        foreach ($viewHelpers as $id => $tags) {
            foreach ($tags as $attributes) {

                $attributes = $this->resolveViewHelperAttributes($attributes);

                if(null == $attributes['alias']) {

                    $helperDef = $container->getDefinition($id);
                    $attributes['alias'] = lcfirst(basename($helperDef->getClass()));
                    //throw new \RuntimeException(sprintf('An alias attribute must be specified in all view helpers. None given for service "%s".', $id));
                }

                $alias = $attributes['alias'];

                switch ($attributes['type']) {
                    case "service":
                        $helperManager->addMethodCall('setService', array($alias, new Reference($id)));
                        break;
                    case "factory":
                        $helperManager->addMethodCall('setFactory', array($alias, new Reference($id)));
                        break;
                    case "invokable":
                        $helperDef = $container->getDefinition($id);
                        $helperManager->addMethodCall('setInvokableClass', array($alias, $helperDef->getClass()));
                        break;
                    default:
                        throw new \InvalidArgumentException(
                            sprintf('Service type can be either "service", "factory", or "invokable". "%s" provided.', $attributes['type']));
                }
            }
        }

        $viewHelpers = $container->findTaggedServiceIds('view_helper');

        foreach ($viewHelpers as $id => $tags) {

            foreach ($tags as $attributes) {

                $attributes = $this->resolveViewHelperAttributes($attributes);

                if(null == $attributes['alias']) {

                    $helperDef = $container->getDefinition($id);
                    $attributes['alias'] = lcfirst(basename($helperDef->getClass()));
                    //throw new \RuntimeException(sprintf('An alias attribute must be specified in all view helpers. None given for service "%s".', $id));
                }

                $decorator = new DefinitionDecorator($id);
                $container->setDefinition(HelpersInContainer::SERVICE_PREFIX. $attributes['alias'], $decorator);

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