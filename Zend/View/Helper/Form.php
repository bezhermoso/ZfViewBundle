<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bez\ZfViewBundle\Zend\View\Helper;

use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Translation\TranslatorInterface;
use Zend\View\Helper\AbstractHelper;

class Form extends AbstractHelper
{
    protected $formRenderer;

    public function __construct(FormRendererInterface $renderer)
    {
        $this->formRenderer = $renderer;
    }

    public function form(FormView $view, array $variables = array())
    {
        return $this->formRenderer->renderBlock($view, 'form', $variables);
    }

    public function start(FormView $view, array $variables = array())
    {
        return $this->formRenderer->renderBlock($view, 'form_start', $variables);
    }

    public function end(FormView $view, array $variables = array())
    {
        return $this->formRenderer->renderBlock($view, 'form_end', $variables);
    }

    public function enctype(FormView $view)
    {
        return $this->formRenderer->searchAndRenderBlock($view, 'enctype');
    }

    public function widget(FormView $view, array $variables = array())
    {
        return $this->formRenderer->searchAndRenderBlock($view, 'widget', $variables);
    }

    public function row(FormView $view, array $variables = array())
    {
        return $this->formRenderer->searchAndRenderBlock($view, 'row', $variables);
    }

    /**
     * Renders the label of the given view.
     *
     * @param FormView $view      The view for which to render the label
     * @param string   $label     The label
     * @param array    $variables Additional variables passed to the template
     *
     * @return string The HTML markup
     */
    public function label(FormView $view, $label = null, array $variables = array())
    {
        if (null !== $label) {
            $variables += array('label' => $label);
        }

        return $this->formRenderer->searchAndRenderBlock($view, 'label', $variables);
    }

    /**
     * Renders the errors of the given view.
     *
     * @param FormView $view The view to render the errors for
     *
     * @return string The HTML markup
     */
    public function errors(FormView $view)
    {
        return $this->formRenderer->searchAndRenderBlock($view, 'errors');
    }

    /**
     * Renders views which have not already been rendered.
     *
     * @param FormView $view      The parent view
     * @param array    $variables An array of variables
     *
     * @return string The HTML markup
     */
    public function rest(FormView $view, array $variables = array())
    {
        return $this->formRenderer->searchAndRenderBlock($view, 'rest', $variables);
    }

    /**
     * Renders a block of the template.
     *
     * @param FormView $view      The view for determining the used themes.
     * @param string   $blockName The name of the block to render.
     * @param array    $variables The variable to pass to the template.
     *
     * @return string The HTML markup
     */
    public function block(FormView $view, $blockName, array $variables = array())
    {
        return $this->formRenderer->renderBlock($view, $blockName, $variables);
    }

    /**
     * Returns a CSRF token.
     *
     * Use this helper for CSRF protection without the overhead of creating a
     * form.
     *
     * <code>
     * echo $view['form']->csrfToken('rm_user_'.$user->getId());
     * </code>
     *
     * Check the token in your action using the same intention.
     *
     * <code>
     * $csrfProvider = $this->get('form.csrf_provider');
     * if (!$csrfProvider->isCsrfTokenValid('rm_user_'.$user->getId(), $token)) {
     *     throw new \RuntimeException('CSRF attack detected.');
     * }
     * </code>
     *
     * @param string $intention The intention of the protected action
     *
     * @return string A CSRF token
     *
     * @throws \BadMethodCallException When no CSRF provider was injected in the constructor.
     */
    public function csrf($intention)
    {
        return $this->formRenderer->renderCsrfToken($intention);
    }

    public function humanize($text)
    {
        return $this->formRenderer->humanize($text);
    }
} 