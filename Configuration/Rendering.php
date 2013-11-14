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
    protected $template;

    protected $layout;

    protected $format = 'html';

    public function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            if(method_exists($this, 'set' . ucfirst($key))) {
                call_user_func_array(array($this, 'set' . ucfirst($key)), array($value));
            }
        }
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function merge(Rendering $rendering)
    {
        if (!$this->getLayout())
            $this->setLayout($rendering->getLayout());

        if (!$this->getTemplate())
            $this->setTemplate($rendering->getTemplate());

    }
} 