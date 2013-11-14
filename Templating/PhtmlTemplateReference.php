<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bzl\Bundle\ZfViewBundle\Templating;


use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;

class PhtmlTemplateReference extends TemplateReference
{
    protected $originalName;

    public function __construct($templateName)
    {
        $this->originalName = $templateName;

        $arguments = func_get_args();
        array_shift($arguments);
        call_user_func_array('parent::__construct', $arguments);
    }

    public function getPath()
    {
        $formatInName = preg_match(sprintf('/\.([^\.]+)\.%s/', $this->get('engine')), $this->originalName);

        $controller = str_replace('\\', '/', $this->get('controller'));

        $path = (empty($controller) ? '' : $controller . '/');
        $path .= $this->get('name') .
                ($formatInName ? '.' . $this->get('format') : '')
                . '.' . $this->get('engine');

        return empty($this->parameters['bundle']) ? 'views/'.$path : '@'.$this->get('bundle').'/Resources/views/'.$path;
    }
}