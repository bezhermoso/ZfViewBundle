<?php
/**
 * Created by PhpStorm.
 * User: Bezalel
 * Date: 11/7/13
 * Time: 8:16 AM
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\EventListener;

use Zend\View\ViewEvent;

class RenderingListener
{
    protected $content;

    public function onRender(ViewEvent $event)
    {
        $this->content = $event->getResult();
    }
} 