<?php
/**
 *
 * User: Bezalel
 * Date: 11/7/13
 * Time: 6:32 AM
 */

namespace Bzl\Bundle\ZfViewBundle\Assetic;


use Assetic\Factory\Loader\FormulaLoaderInterface;
use Assetic\Factory\Resource\ResourceInterface;

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
        // TODO: Implement load() method.
    }
}