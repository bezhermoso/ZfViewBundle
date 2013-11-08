<?php
/**
 *
 * User: Bezalel
 * Date: 11/7/13
 * Time: 1:41 PM
 */

namespace Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ViewHelpersPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
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