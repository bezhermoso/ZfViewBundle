<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Bez\ZfViewBundle\Zend\Stdlib;


use Zend\Stdlib\RequestInterface;
use Symfony\Component\HttpFoundation\Request as SfRequest;

/**
 * Class Request
 *
 * Decorates Symfony\HttpFoundation\Request with Zend\Stdlib\RequestInterface
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bez\ZfViewBundle\Zend\Stdlib
 */

class Request implements RequestInterface
{
    protected $metadata;

    protected $request;

    public function __construct(SfRequest $request)
    {
        $this->request = $request;
        $this->metadata = array();
    }
    /**
     * Set metadata
     *
     * @param  string|int|array|\Traversable $spec
     * @param  mixed $value
     */
    public function setMetadata($spec, $value = NULL)
    {
        if (is_array($spec)) {
            $this->metadata = $spec;
        } else {
            $this->metadata[$spec] = $value;
        }
    }

    /**
     * Get metadata
     *
     * @param  null|string|int $key
     * @return mixed
     */
    public function getMetadata($key = NULL)
    {
        if (isset($this->metadata[$key])) {
            return $this->metadata[$key];
        }
        return;
    }

    /**
     * Set content
     *
     * @param  mixed $content
     * @return mixed
     */
    public function setContent($content)
    {
        return;
    }

    /**
     * Get content
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->request->getContent();
    }
}