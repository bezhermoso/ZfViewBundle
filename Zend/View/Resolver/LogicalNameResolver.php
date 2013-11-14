<?php
/**
 *
 * User: Bezalel
 * Date: 11/7/13
 * Time: 3:06 AM
 */

namespace Bez\ZfViewBundle\Zend\View\Resolver;


use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Templating\Loader\LoaderInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Zend\View\Renderer\RendererInterface as Renderer;
use Zend\View\Resolver\ResolverInterface;

/**
 * Class LogicalNameResolver
 *
 * Resolves Symfony's template names to their actual paths for use in ViewModel.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bez\ZfViewBundle\Zend\View\Resolver
 */
class LogicalNameResolver implements ResolverInterface
{
    protected $loader;

    protected $parser;

    protected $locator;

    /**
     * @param LoaderInterface $loader
     * @param TemplateNameParserInterface $parser
     * @param \Symfony\Component\Config\FileLocatorInterface $locator
     */
    public function __construct(
        LoaderInterface $loader,
        TemplateNameParserInterface $parser,
        FileLocatorInterface $locator
    ) {
        $this->loader = $loader;
        $this->parser = $parser;
        $this->locator = $locator;
    }

    /**
     * Resolve a template/pattern name to a resource the renderer can consume
     *
     * @param  string $name
     * @param  null|Renderer $renderer
     * @return mixed
     */
    public function resolve($name, Renderer $renderer = NULL)
    {
        $template = $this->parser->parse($name);
        $path = $this->locator->locate($template);
        return $path;
    }
}