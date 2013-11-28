<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bez\ZfViewBundle\View\Helper;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Zend\View\Helper\AbstractHelper;

class Request extends AbstractHelper
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke()
    {
        if ($this->container->has('request') && $request = $this->container->get('request')) {
            return $request;
        }
    }
}