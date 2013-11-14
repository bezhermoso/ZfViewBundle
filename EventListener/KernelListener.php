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
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Sensio\Bundle\FrameworkExtraBundle\Templating\TemplateGuesser;
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


    public function __construct(
        EventManager $events,
        Reader $reader,
        ZfViewEngine $engine,
        TemplateGuesser $guesser
    ) {
        $this->eventManager = $events;
        $this->reader = $reader;
        $this->engine = $engine;
        $this->guesser = $guesser;
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

        if (is_array($results)) {

            $request = $event->getRequest();

            if (!$request->attributes->has('__rendering')) {
                return;
            }

            /** @var $rendering Rendering */
            $rendering = $request->attributes->get('__rendering');
            $this->eventManager->trigger('render', $event);

            $viewModel = new ViewModel();
            $viewModel->setVariables($results);
            $viewModel->setTemplate($rendering->getViewName());
            $viewModel->setOption('formatAs', $rendering->getFormat());

            if ($rendering->getTemplate() != null
            && strtolower($rendering->getTemplate()) != "none") {
                $template = new ViewModel();
                $template->setTemplate($rendering->getTemplate());
                $template->addChild($viewModel);
                $viewModel = $template;
                unset($template);
            }

        } elseif ($results instanceof ViewModel) {
            $viewModel = $results;
        }

        if(null !== $viewModel) {
            $response = $this->engine->renderResponse($viewModel);
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

            if (!$methodRenderingConfig->getViewName()) {
                $name = $this->guesser->guessTemplateName($controller, $request, 'phtml');
                $methodRenderingConfig->setViewName($name->getLogicalName());
            }
        }

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