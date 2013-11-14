<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Bez\ZfViewBundle\DependencyInjection\CompilerPass;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class EventManagerListenersPass
 *
 * Attaches tagged listeners to Zend\EventManager\EventManager instance
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bez\ZfViewBundle\DependencyInjection\CompilerPass
 */
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