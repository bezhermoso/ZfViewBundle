<?php
/**
 *
 * User: Bezalel
 * Date: 11/7/13
 * Time: 10:21 AM
 */

namespace Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EventManagerListenersPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('zend.event_manager')) {
            return;
        }

        $eventManager = $container->getDefinition('zend.event_manager');

        $listeners = $container->findTaggedServiceIds('zend.event_listener');

        foreach ($listeners as $id => $tags) {

            $priority = 1;

            foreach ($tags as $attributes) {
                $priority = isset($attributes['priority']) ? (int) $attributes['priority'] : $priority;
            }

            $eventManager
                ->addMethodCall('attach', array(new Reference($id), $priority));
        }

    }
}