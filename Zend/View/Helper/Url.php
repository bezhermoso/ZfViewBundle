<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\View\Helper;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Zend\View\Helper\AbstractHelper;

class Url extends AbstractHelper
{
    protected $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function __invoke($routeName, array $params = null, $relative = false)
    {
        return $this->generator->generate(
                            $routeName,
                            $params ?: array(),
                            $relative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL);
    }
} 