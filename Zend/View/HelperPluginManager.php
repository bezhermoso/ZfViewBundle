<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\View;

use Zend\ServiceManager\ConfigInterface;
use Zend\View\HelperPluginManager as ZfPluginManager;

class HelperPluginManager extends ZfPluginManager
{

    public function __construct(ConfigInterface $configuration = NULL)
    {
        $this->factories = array();

        $this->plugins = array(
            'doctype'             => 'Zend\View\Helper\Doctype', // overridden by a factory in ViewHelperManagerFactory
            //'basepath'            => 'Zend\View\Helper\BasePath',
            //'url'                 => 'Zend\View\Helper\Url',
            'cycle'               => 'Zend\View\Helper\Cycle',
            //'declarevars'         => 'Zend\View\Helper\DeclareVars',
            'escapehtml'          => 'Zend\View\Helper\EscapeHtml',
            'escapehtmlattr'      => 'Zend\View\Helper\EscapeHtmlAttr',
            'escapejs'            => 'Zend\View\Helper\EscapeJs',
            'escapecss'           => 'Zend\View\Helper\EscapeCss',
            'escapeurl'           => 'Zend\View\Helper\EscapeUrl',
            //'gravatar'            => 'Zend\View\Helper\Gravatar',
            'headlink'            => 'Zend\View\Helper\HeadLink',
            'headmeta'            => 'Zend\View\Helper\HeadMeta',
            'headscript'          => 'Zend\View\Helper\HeadScript',
            'headstyle'           => 'Zend\View\Helper\HeadStyle',
            'headtitle'           => 'Zend\View\Helper\HeadTitle',
            //'htmlflash'           => 'Zend\View\Helper\HtmlFlash',
            //'htmllist'            => 'Zend\View\Helper\HtmlList',
            //'htmlobject'          => 'Zend\View\Helper\HtmlObject',
            //'htmlpage'            => 'Zend\View\Helper\HtmlPage',
            //'htmlquicktime'       => 'Zend\View\Helper\HtmlQuicktime',
            'inlinescript'        => 'Zend\View\Helper\InlineScript',
            //'json'                => 'Zend\View\Helper\Json',
            'layout'              => 'Zend\View\Helper\Layout',
            //'paginationcontrol'   => 'Zend\View\Helper\PaginationControl',
            'partialloop'         => 'Zend\View\Helper\PartialLoop',
            'partial'             => 'Zend\View\Helper\Partial',
            'placeholder'         => 'Zend\View\Helper\Placeholder',
            //'renderchildmodel'    => 'Zend\View\Helper\RenderChildModel',
            //'rendertoplaceholder' => 'Zend\View\Helper\RenderToPlaceholder',
            //'serverurl'           => 'Zend\View\Helper\ServerUrl',
            'viewmodel'           => 'Zend\View\Helper\ViewModel',
        );

        parent::__construct($configuration);
    }
}