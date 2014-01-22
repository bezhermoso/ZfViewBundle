<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bez\ZfViewBundle\Templating;


use Bez\ZfViewBundle\View\PluginManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Zend\View\Model\ViewModel;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\View;

class TimedZfViewEngine extends ZfViewEngine
{
    protected $stopwatch;

    public function __construct(
        View $view,
        ResolverInterface $resolver,
        ContainerInterface $container,
        PluginManagerInterface $plugins,
        Stopwatch $stopwatch,
        GlobalVariables $globals = null
    ) {

        parent::__construct($view, $resolver, $container, $plugins, $globals);
        $this->stopwatch = $stopwatch;

    }

    public function render($name, array $parameters = array())
    {
        if($name instanceof ViewModel) {
            $templateName = $name->getTemplate();
        } else {
            $templateName = $name;
        }

        $e = $this->stopwatch->start(sprintf('template.zf_view %s', $templateName), 'template');

        $return = parent::render($name, $parameters);

        $e->stop();

        return $return;

    }
} 