<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Bez\ZfViewBundle\Configuration;

/**
 * Class Rendering
 *
 * Annotation to define view files and other options.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bez\ZfViewBundle\Configuration
 *
 * @Annotation
 */
class Rendering
{
    protected $viewName;

    protected $template;

    protected $format = 'html';

    public function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            if(method_exists($this, 'set' . ucfirst($key))) {
                call_user_func_array(array($this, 'set' . ucfirst($key)), array($value));
            }
        }
    }

    public function setValue($value)
    {
        $this->setViewName($value);
    }

    public function setViewName($viewName)
    {
        $this->viewName = $viewName;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function getViewName()
    {
        return $this->viewName;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function merge(Rendering $rendering)
    {
        if (!$this->getViewName())
            $this->setViewName($rendering->getViewName());

        if (!$this->getTemplate())
            $this->setTemplate($rendering->getTemplate());

    }
} 