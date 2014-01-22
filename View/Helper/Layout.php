<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bez\ZfViewBundle\View\Helper;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class Layout extends AbstractHelper
{
    protected $container;

    protected $layout;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ViewModel
     * @throws \DomainException
     */
    public function getViewModel()
    {
        if (!$this->container->has('bez.view_model')) {
            throw new \DomainException('No view model has been assigned for this request yet.');
        } else {
            return $this->container->get('bez.view_model');
        }
    }

    public function __invoke($layout)
    {
        $this->layout = (string) $layout;
    }

    public function getLayout()
    {
        return $this->layout;
    }
}