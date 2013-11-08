<?php
/**
 * Created by PhpStorm.
 * User: Bezalel
 * Date: 11/7/13
 * Time: 8:32 AM
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\View;


use Zend\View\Model\ViewModel;

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