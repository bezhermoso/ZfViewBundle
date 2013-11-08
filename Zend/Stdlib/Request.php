<?php
/**
 *
 * User: Bezalel
 * Date: 11/7/13
 * Time: 7:20 AM
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\Stdlib;


use Zend\Stdlib\RequestInterface;
use Symfony\Component\HttpFoundation\Request as SfRequest;


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
        // TODO: Implement setContent() method.
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