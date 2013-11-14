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
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Templating\Loader\FilesystemLoader;

/**
 * Class TemplatingPass
 *
 * Registers overrides to default functionality like template name parsing.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bzl\Bundle\ZfViewBundle\DependencyInjection\CompilerPass
 */
class TemplatingPass implements CompilerPassInterface
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

        $origDefinition = $container->getDefinition('templating.name_parser');
        $container->removeDefinition('templating.name_parser');
        $container->setDefinition('templating.name_parser.original', $origDefinition);

        $container->register('templating.name_parser', 'Bzl\Bundle\ZfViewBundle\Templating\TemplateNameParser')
                  ->addArgument(new Reference('templating.name_parser.original'));

    }
}