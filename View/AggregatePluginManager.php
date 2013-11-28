<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bez\ZfViewBundle\View;

use Bez\ZfViewBundle\View\PluginManagerInterface;
use Zend\Stdlib\SplPriorityQueue;
use Zend\View\HelperPluginManager;

/**
 * Class AggregatePluginManager
 *
 * Aggregates view helper managers.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bez\ZfViewBundle\View
 */
class AggregatePluginManager extends HelperPluginManager implements PluginManagerInterface
{
    /**
     * @var \Zend\Stdlib\SplPriorityQueue|PluginManagerInterface[]
     */
    protected $managers;

    public function __construct()
    {
        $this->managers = new SplPriorityQueue();
    }

    public function get($name, $options = array(), $usePeeringServiceManagers = TRUE)
    {
        $managers = $this->managers->toArray();

        ksort($managers);

        foreach ($managers as $manager) {

            if ($manager->has($name, true, $usePeeringServiceManagers)) {
                $plugin = $manager->get($name, $options, $usePeeringServiceManagers);
                $this->initializePlugin($plugin);
                return $plugin;
            }
        }

        throw new \RuntimeException(
            sprintf('Cannot find view helper "%s" in any of the plugin managers!', $name));
    }

    public function initializePlugin($plugin)
    {
        $this->validatePlugin($plugin);
        $this->injectRenderer($plugin);
        $this->injectTranslator($plugin);
    }

    public function has($name, $checkAbstractFactories = TRUE, $usePeeringServiceManagers = TRUE)
    {
        $has = false;

        $managers = $this->managers->toArray();

        ksort($managers);

        foreach ($managers as $manager) {
            $has = ($has OR $manager->has($name, $checkAbstractFactories, $usePeeringServiceManagers));
        }

        return $has;
    }

    public function addPluginManager($pluginManager, $priority = 1)
    {
        if ($pluginManager instanceof HelperPluginManager OR $pluginManager instanceof PluginManagerInterface) {

            $this->managers->insert($pluginManager, $priority);

        } else {
            throw new \InvalidArgumentException(
                sprintf('Expected instance of "%s" or "%s". Instance of "%s" given.',
                        'Bez\ZfViewBundle\View\PluginManagerInterface',
                        'Zend\View\HelperPluginManager',
                        is_object($pluginManager) ? get_class($pluginManager) : $pluginManager));
        }
    }
}