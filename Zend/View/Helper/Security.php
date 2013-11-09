<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bzl\Bundle\ZfViewBundle\Zend\View\Helper;


use Symfony\Component\Security\Acl\Voter\FieldVote;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zend\View\Helper\AbstractHelper;

class Security extends AbstractHelper
{
    protected $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function __invoke()
    {
        return $this;
    }

    public function isGranted($role, $object = null, $field = null)
    {
        if (null === $this->securityContext) {
            return false;
        }

        if (null !== $field) {
            $object = new FieldVote($object, $field);
        }

        return $this->securityContext->isGranted($role, $object);
    }

    public function user()
    {
        if (null === $this->securityContext) {
            return null;
        }

        return $this->securityContext->getToken() ? $this->securityContext->getToken() : $this->securityContext->getToken()->getUser();
    }
}