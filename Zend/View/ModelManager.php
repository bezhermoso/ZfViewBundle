<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\View;


use Zend\View\Model\ViewModel;

/**
 * Class ModelManager
 *
 * @todo Implement
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bzl\Bundle\ZfViewBundle\Zend\View
 */
class ModelManager
{
    protected $model;

    /**
     *
     */
    public function __construct()
    {
        $this->model = new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param ViewModel $model
     */
    public function setModel(ViewModel $model)
    {
        $this->model = $model;
    }
} 