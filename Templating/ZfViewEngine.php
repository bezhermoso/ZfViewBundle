<?php
/**
 *
 * User: Bezalel
 * Date: 11/7/13
 * Time: 6:18 AM
 */

namespace Bez\ZfViewBundle\Templating;

use Bez\ZfViewBundle\Configuration\Rendering;
use Bez\ZfViewBundle\View\Helper\Layout;
use Bez\ZfViewBundle\View\PluginManagerInterface;
use Bez\ZfViewBundle\Zend\Stdlib\Request;
use Bez\ZfViewBundle\Zend\Stdlib\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zend\EventManager\EventManager;
use Zend\View\Exception\RuntimeException;
use Zend\View\Model\ViewModel;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\View;
use Symfony\Component\HttpFoundation\Response as SfResponse;

/**
 * Class ZfViewEngine
 *
 * Rendering engine integrating Zend\View component.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bez\ZfViewBundle
 */
class ZfViewEngine implements EngineInterface
{
    protected $resolver;

    protected $globals;

    protected $view;

    protected $container;

    protected $result;

    protected $plugins;

    /**
     * @param View $view
     * @param ResolverInterface $resolver
     * @param ContainerInterface $container
     * @param \Bez\ZfViewBundle\View\PluginManagerInterface $plugins
     * @param GlobalVariables $globals
     */
    public function __construct(
        View $view,
        ResolverInterface $resolver,
        ContainerInterface $container,
        PluginManagerInterface $plugins,
        GlobalVariables $globals = null
    ) {

        $this->view = $view;
        $this->globals = array();
        $this->resolver = $resolver;
        $this->container = $container;
        $this->plugins = $plugins;

        if (null !== $globals) {
            $this->addGlobal('app', $globals);
        }

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

            $result = $this->resolver->resolve($name);
            return (bool) $result;

        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     *
     * Checks if engine supports template.
     * Supports: *.phtml files and instances of ViewModel
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
     * @param string $view
     * @param array $parameters
     * @param SfResponse $sfResponse
     * @internal param \Bez\ZfViewBundle\Configuration\Rendering $rendering
     * @internal param \Bez\ZfViewBundle\Configuration\Rendering $rendering
     * @internal param bool $useRendering
     * @return SfResponse
     */
    public function renderResponse($view, array $parameters = array(), SfResponse $sfResponse = NULL)
    {
        /** @var $layout Layout */

        if (is_string($view)) {

            $result = $this->render($view, $parameters);

        } elseif ($view instanceof ViewModel) {
            $viewModel = $view;
            $viewModel->setVariables($parameters);
            $result = $this->render($viewModel);
        }


        if (null === $sfResponse) {
            $sfResponse = new SfResponse();
        }

        $sfResponse->setContent($result);

        return $sfResponse;
        /*
        $response = new Response($sfResponse);
        $this->view->setResponse($response);

        $sfRequest = $this->container->get('request');
        $request = new Request($sfRequest);

        $this->view->setRequest($request);
        $this->view->render($viewModel, $parameters);
        */

    }

    /**
     * Renders a template.
     *
     * @param mixed $name A template name or a TemplateReferenceInterface instance
     * @param array $parameters An array of parameters to pass to the template
     *
     * @param bool $isRoot
     * @throws \InvalidArgumentException
     * @return string The evaluated template as a string
     *
     * @api
     */
    public function render($name, array $parameters = array(), $isRoot = null)
    {
        if (is_string($name)) {

            $root = new ViewModel();
            $root->setTemplate('ZfViewBundle::layout.phtml');

            $prevRoot = $this->getModelHelper()->getRoot();
            $this->getModelHelper()->setRoot($root);

            $viewModel = new ViewModel();
            $viewModel->setTemplate($name);
            $viewModel->setVariables($parameters);

            $root->addChild($viewModel);
            $viewModel = $root;

        } elseif ($name instanceof ViewModel) {
            $viewModel = $name;
            $viewModel->setVariables($parameters);
        } else {
            throw new \InvalidArgumentException(
                                sprintf(
                                    'Expected string or instance of Zend\View\Model\ViewModel. %s given.',
                                    get_class($name)
                                ));
        }

        $viewModel->setVariables($this->getGlobals());

        $viewModel->setOption('has_parent', true);
        $result = $this->view->render($viewModel);

        if (is_string($name)) {
            $this->getModelHelper()->setRoot($prevRoot);
        }

        return $result;

    }

    /**
     * @return \Zend\View\Helper\ViewModel
     */
    private function getModelHelper()
    {
        return $this->plugins->get('view_model');
    }
}