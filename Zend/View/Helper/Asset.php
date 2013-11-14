<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\View\Helper;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Zend\View\Helper\AbstractHelper;

class Asset extends AbstractHelper
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($asset, $packageName = null)
    {
        return $this->container->get('templating.helper.assets')->getUrl($asset, $packageName);
    }
} 