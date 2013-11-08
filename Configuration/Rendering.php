<?php
/**
 *
 * User: Bezalel
 * Date: 11/7/13
 * Time: 11:47 AM
 */

namespace Bzl\Bundle\ZfViewBundle\Configuration;

/**
 * Class Rendering
 * @package Bzl\Bundle\ZfViewBundle\Configuration
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