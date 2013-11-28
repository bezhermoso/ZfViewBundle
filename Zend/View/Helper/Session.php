<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bez\ZfViewBundle\Zend\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Session extends AbstractHelper
{
    public function __invoke()
    {
        if ($request = $this->getView()->request()) {
            return $request->getSession();
        }
    }
} 