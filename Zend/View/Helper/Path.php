<?php
/**
 * Created by PhpStorm.
 * User: Bezalel
 * Date: 11/7/13
 * Time: 1:33 PM
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\View\Helper;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Zend\View\Helper\AbstractHelper;

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