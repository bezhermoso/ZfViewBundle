<?php
/**
 *
 * User: Bezalel
 * Date: 11/7/13
 * Time: 6:18 AM
 */

namespace Bzl\Bundle\ZfViewBundle;

use Bzl\Bundle\ZfViewBundle\Zend\Stdlib\Request;
use Bzl\Bundle\ZfViewBundle\Zend\Stdlib\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zend\View\Exception\RuntimeException;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\View;
use Symfony\Component\HttpFoundation\Response as SfResponse;
use Symfony\Component\HttpFoundation\Request as SfRequest;
use Zend\View\ViewEvent;

class ZfViewEngine implements EngineInterface
{
    protected $resolver;

    protected $globals;

    protected $view;

    protected $container;

    protected $result;

    /**
     * @param \Zend\View\View $view
     * @param \Zend\View\Resolver\ResolverInterface $resolver
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param GlobalVariables $globals
     * @internal param \Zend\View\Renderer\RendererInterface $renderer
     */
    public function __construct(
        View $view,
        ResolverInterface $resolver,
        ContainerInterface $container,
        GlobalVariables $globals = null
    ) {

        $this->view = $view;
        $this->globals = array();
        $this->resolver = $resolver;
        $this->container = $container;

        if (null !== $globals) {
            $this->addGlobal('app', $globals);
        }

        $this->attachListeners();

    }

    public function attachListeners()
    {
        $events = $this->view->getEventManager();
        $container = $this->container;
        $engine = $this;
    }

    public function getGlobals()
    {
        return $this->globals;
    }

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function addGlobal($name, $variable)
    {
        $this->globals[$name] = $variable;
        return $this;
    }

    /**
     * Renders a template.
     *
     * @param mixed $name A template name or a TemplateReferenceInterface instance
     * @param array $parameters An array of parameters to pass to the template
     *
     * @throws \Exception
     * @throws \Zend\View\Exception\RuntimeException
     * @throws \Exception
     * @return string The evaluated template as a string
     *
     * @api
     */
    public function render($name, array $parameters = array())
    {
        try {

            $data = array_merge((array) $this->getGlobals(), $parameters);
            $this->view->render($name, $data);
            return $this->getResult();

        } catch(RuntimeException $e) {
            throw $e;
        } catch(\Exception $e) {
            throw $e;
        }
        // TODO: Implement render() method.
    }

    /**
     * Returns true if the template exists.
     *
     * @param mixed $name A template name or a TemplateReferenceInterface instance
     *
     * @return Boolean true if the template exists, false otherwise
     *
     * @api
     */
    public function exists($name)
    {
        try {

            $result = $this->resolver->resolve($name, $this->renderer);
            return (bool) $result;

        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Returns true if this class is able to render the given template.
     *
     * @param mixed $name A template name or a TemplateReferenceInterface instance
     *
     * @return Boolean true if this class supports the given template, false otherwise
     *
     * @api
     */
    public function supports($name)
    {
        if ($name instanceof ViewModel) {
            return true;
        } elseif (is_string($name)) {
            if(preg_match('/\.phtml$/', $name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Renders a view and returns a Response.
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     * @param SfResponse $response A Response instance
     *
     * @return Response A Response instance
     */
    public function renderResponse($view, array $parameters = array(), SfResponse $sfResponse = NULL)
    {
        if(!$view instanceof ViewModel) {
            return;
        }

        $variables = $view->getVariables();
        $view->setVariables(array_merge($this->getGlobals(), (array) $variables));

        if (null === $sfResponse) {
            $sfResponse = new SfResponse();
        }

        $response = new Response($sfResponse);
        $this->view->setResponse($response);

        $sfRequest = $this->container->get('request');
        $request = new Request($sfRequest);

        $this->view->setRequest($request);


        $rendered = $this->render($view, $parameters);

        return $sfResponse;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function getResult()
    {
        return $this->result;
    }
}