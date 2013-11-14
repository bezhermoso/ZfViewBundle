<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bez\ZfViewBundle\Templating;


use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;

/**
 * Class PhtmlTemplateReference
 *
 * Template reference for .phtml files. Holds extra logic for cases when 'format' part is not defined in template name.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bez\ZfViewBundle\Templating
 */
class PhtmlTemplateReference extends TemplateReference
{
    protected $originalName;

    /**
     * @inheritdoc
     */
    public function __construct($templateName)
    {
        $this->originalName = $templateName;

        $args = func_get_args();
        array_shift($args);
        call_user_func_array('parent::__construct', $args);

    }

    /**
     * @return string
     */
    public function getPath()
    {
        $controller = str_replace('\\', '/', $this->get('controller'));
        $path = (empty($controller) ? '' : $controller . '/');
        $path .= $this->get('name') . ($this->get('format') ? '.' . $this->get('format') : '')
                . '.' . $this->get('engine');

        return empty($this->parameters['bundle']) ? 'views/'.$path : '@'.$this->get('bundle').'/Resources/views/'.$path;
    }

    /**
     * @param TemplateReference $reference
     * @return static
     */
    public function createFromTemplateReference(TemplateReference $reference)
    {
        $phtmlReference = new static(
                                $reference->getLogicalName(),
                                $reference->get('bundle'),
                                $reference->get('controller'),
                                $reference->get('name'),
                                $reference->get('format'),
                                $reference->get('engine'));

        return $phtmlReference;

    }

    /**
     * @return string
     */
    public function getLogicalName()
    {
        $bundlePart = implode(':', array($this->get('bundle'), $this->get('controller')));
        $filePart = implode('.', array_filter(array($this->get('name'), $this->get('format'), $this->get('engine'))));
        return $bundlePart . ':' . $filePart;
    }
}