<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\View\Helper;


use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Zend\View\Helper\AbstractHelper;

/**
 * Class ControllerRenderer
 *
 * Equivalent to the 'render(controller(...))' Twig function call.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bzl\Bundle\ZfViewBundle\Zend\View\Helper
 */
class ControllerRenderer extends AbstractHelper
{
    protected $handler;

    protected $controller;

    public function __construct(FragmentHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke($controllerName, $attributes = array(), $query = array())
    {
        $this->controller = new ControllerReference($controllerName, $attributes, $query);
        return $this;
    }

    public function render($options = array())
    {
        $strategy = isset($options['strategy']) ? $options['strategy'] : 'inline';
        unset($options['strategy']);

        $controller = $this->controller;
        $this->controller = null;

        return $this->handler->render($controller, $strategy, $options);
    }
}