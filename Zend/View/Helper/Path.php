<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\View\Helper;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Path
 *
 * Defines path generator helper for use within views.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bzl\Bundle\ZfViewBundle\Zend\View\Helper
 */
class Path extends AbstractHelper
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function __invoke($routeName, $params, $useAbsolute = false)
    {
        return $this->router->generate($routeName, $params, $useAbsolute);
    }
} 