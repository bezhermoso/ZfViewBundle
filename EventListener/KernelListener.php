<?php
/**
 *
 * User: Bezalel
 * Date: 11/7/13
 * Time: 10:52 AM
 */

namespace Bzl\Bundle\ZfViewBundle\EventListener;


use Bzl\Bundle\ZfViewBundle\Configuration\Rendering;
use Bzl\Bundle\ZfViewBundle\ZfViewEngine;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelEvents;
use Zend\EventManager\EventManager;
use Zend\View\Model\ViewModel;

class KernelListener implements EventSubscriberInterface
{
    protected $eventManager;
    protected $reader;
    protected $engine;

    public function __construct(EventManager $events, Reader $reader, ZfViewEngine $engine)
    {
        $this->eventManager = $events;
        $this->reader = $reader;
        $this->engine = $engine;
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
            KernelEvents::RESPONSE => 'onResponse',
            KernelEvents::VIEW => 'onView',
        );
        // TODO: Implement getSubscribedEvents() method.
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

            if ($rendering->getTemplate() != null) {
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

        $className = class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($controller[0]) : get_class($controller[0]);
        $object    = new \ReflectionClass($className);
        $method    = $object->getMethod($controller[1]);

        $classRenderingConfig = $this->getRenderingConfiguration($this->reader->getClassAnnotations($object));
        $methodRenderingConfig = $this->getRenderingConfiguration($this->reader->getMethodAnnotations($method));

        if ($methodRenderingConfig) {
            $methodRenderingConfig->merge($classRenderingConfig);
        }

        $request = $event->getRequest();

        $request->attributes->set('__rendering', $methodRenderingConfig);


    }

    /**
     * @param array $annotations
     * @return Rendering
     */
    protected function getRenderingConfiguration(array $annotations)
    {
        $configurations = array();
        foreach ($annotations as $configuration) {
            if ($configuration instanceof Rendering) {
                return $configuration;
            }
        }
    }

    public function onResponse(FilterResponseEvent $event)
    {
        //$this->eventManager->trigger('')
    }
}