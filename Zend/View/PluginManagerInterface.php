<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bez\ZfViewBundle\Zend\View;

/**
 * Interface PluginManagerInterface
 *
 * Defines contract for view helper managers.
 * Mainly used for Bzl/Bundle/ZfViewBundle/Zend/View/AggregatePluginManager
 *
 * @package Bez\ZfViewBundle\Zend\View
 */
interface PluginManagerInterface
{
    public function get($name, $options = array(), $usePeeringServiceManagers = TRUE);

    public function has($name, $checkAbstractFactories = TRUE, $usePeeringServiceManagers = TRUE);
} 