<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 *
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php
 */

namespace Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ViewHelpersPass
 *
 * Registers tagged view helpers.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass
 */
class ViewHelpersPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.     *
     * @param ContainerBuilder $container
     * @throws \RuntimeException
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bzl.zfview.helper_manager')) {
            return;
        }

        $helperManager = $container->getDefinition('bzl.zfview.helper_manager');

        $viewHelpers = $container->findTaggedServiceIds('zend.view_helper');

        foreach ($viewHelpers as $id => $tags) {
            foreach ($tags as $attributes) {
                $attributes = $this->resolveAttributes($attributes);

                if(null == $attributes['alias']) {
                    throw new \RuntimeException(sprintf('An alias attribute must be specified in all view helpers. None given for service "%s".', $id));
                }
                $alias = $attributes['alias'];

                switch ($attributes['type']) {
                    default:
                        $helperManager->addMethodCall('setService', array($alias, new Reference($id)));
                        break;
                }
            }
        }
    }

    public function resolveAttributes(array $attributes)
    {
        return array_merge(array(
            'type' => 'service',
            'alias' => null,
        ), $attributes);
    }
}