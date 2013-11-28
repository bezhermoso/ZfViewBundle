<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bez\ZfViewBundle\Zend\View\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Zend\View\Helper\AbstractHelper;

class Kernel extends AbstractHelper
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function env()
    {
        return $this->container->getParameter('kernel.environment');
    }

    public function debug()
    {
        return $this->container->getParameter('kernel.debug');
    }
} 