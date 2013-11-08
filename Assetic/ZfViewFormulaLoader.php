<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Bzl\Bundle\ZfViewBundle\Assetic;


use Assetic\Factory\Loader\FormulaLoaderInterface;
use Assetic\Factory\Resource\ResourceInterface;

/**
 * Class ZfViewFormulaLoader
 *
 * Assetic formulae loader for ZfViewBundle.
 *
 * @todo Implement formula loader for ZfViewBundle
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bzl\Bundle\ZfViewBundle\Assetic
 */
class ZfViewFormulaLoader implements FormulaLoaderInterface
{

    /**
     * Loads formulae from a resource.
     *
     * Formulae should be loaded the same regardless of the current debug
     * mode. Debug considerations should happen downstream.
     *
     * @param ResourceInterface $resource A resource
     *
     * @return array An array of formulae
     */
    public function load(ResourceInterface $resource)
    {
        return array();
    }
}