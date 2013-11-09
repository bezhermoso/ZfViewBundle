<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\View;


use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class HelpersInContainer
 *
 * Pulls view helpers from the Symfony2 service container.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bzl\Bundle\ZfViewBundle\Zend\View
 */
class HelpersInContainer implements PluginManagerInterface
{

    const SERVICE_PREFIX = 'view_helper.';

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function get($name, $options = array(), $usePeeringServiceManagers = TRUE)
    {
        return $this->container->get(self::SERVICE_PREFIX . $name);
    }

    public function has($name, $checkAbstractFactories = TRUE, $usePeeringServiceManagers = TRUE)
    {
        return $this->container->has(self::SERVICE_PREFIX . strtolower($name));
    }
}