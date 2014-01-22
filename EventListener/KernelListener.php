<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Bez\ZfViewBundle\EventListener;

use Bez\ZfViewBundle\Configuration\Rendering;
use Bez\ZfViewBundle\Templating\ZfViewEngine;
use Bez\ZfViewBundle\View\PluginManagerInterface;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Sensio\Bundle\FrameworkExtraBundle\Templating\TemplateGuesser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Zend\EventManager\EventManager;
use Zend\View\Model\ViewModel;

/**
 * Class KernelListener
 *
 * Hooks into kernel events in order to determine the rendering options defined in annotations.
 * Also resolves an array or ViewModel controller outputs into a Response object.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bez\ZfViewBundle\EventListener
 */

class KernelListener implements EventSubscriberInterface
{
    protected $eventManager;

    protected $reader;

    protected $engine;

    protected $guesser;

    protected $container;

    protected $plugins;

    public function __construct(
        EventManager $events,
        Reader $reader,
        ZfViewEngine $engine,
        TemplateGuesser $guesser,
        PluginManagerInterface $plugins,
        ContainerInterface $container
    ) {
        $this->eventManager = $events;
        $this->reader = $reader;
        $this->engine = $engine;
        $this->guesser = $guesser;
        $this->container = $container;
        $this->plugins = $plugins;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onController',
            KernelEvents::VIEW => 'onView',
        );
    }

    public function onView(GetResponseForControllerResultEvent $event)
    {
        $viewModel = null;

        $results = $event->getControllerResult();

        $layout = $this->plugins->get('layout');

        if (is_array($results)) {

            $request = $event->getRequest();

            if (!$request->attributes->has('__rendering')) {
                return;
            }

            /** @var $rendering Rendering */
            $rendering = $request->attributes->get('__rendering');

            $this->eventManager->trigger('render', $event);

            $child = new ViewModel($results);
            $child->setTemplate($rendering->getTemplate());


            if ($rendering->hasLayout()) {
                $viewModel = $this->container->get('bez.view_model');
                $viewModel->setTemplate($rendering->getLayout());
                $viewModel->addChild($child);
                $viewModel->setOption('rendering', $rendering);
            } else {
                $viewModel = $child;
                $viewModel->setTerminal(true);
            }

            $response = $this->engine->renderResponse($viewModel, $results, $event->getResponse());
            $event->setResponse($response);

        } elseif ($results instanceof ViewModel) {

            $response = $this->engine->renderResponse($results, array(), $event->getResponse());
            $event->setResponse($response);

        }
    }

    public function onController(FilterControllerEvent $event)
    {

        if (!is_array($controller = $event->getController())) {
            return;
        }

        $request = $event->getRequest();

        $className = class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($controller[0]) : get_class($controller[0]);
        $object    = new \ReflectionClass($className);
        $method    = $object->getMethod($controller[1]);

        $classRenderingConfig = $this->getRenderingConfiguration($this->reader->getClassAnnotations($object));
        $methodRenderingConfig = $this->getRenderingConfiguration($this->reader->getMethodAnnotations($method));


        if ($methodRenderingConfig) {

            if ($classRenderingConfig) {
                $methodRenderingConfig->merge($classRenderingConfig);
            }

            if (!$methodRenderingConfig->getTemplate()) {
                $name = $this->guesser->guessTemplateName($controller, $request, 'phtml');
                $methodRenderingConfig->setTemplate($name->getLogicalName());
            }
        }

        /** @var $viewModel \Zend\View\Helper\ViewModel */
        $viewModel = $this->plugins->get('view_model');
        $viewModel->setRoot($this->container->get('bez.view_model'));

        $request->attributes->set('__rendering', $methodRenderingConfig);
    }

    /**
     * @param array $annotations
     * @return Rendering
     */
    protected function getRenderingConfiguration(array $annotations)
    {
        foreach ($annotations as $configuration) {
            if ($configuration instanceof Rendering) {
                return $configuration;
            }
        }
        return null;
    }
}