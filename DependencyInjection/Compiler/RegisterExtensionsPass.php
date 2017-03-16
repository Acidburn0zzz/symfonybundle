<?php
/**
 * Copyright (c) 2017
 *
 * @category  Library
 * @package   Dwoo\SymfonBundle\DependencyInjection\Compiler
 * @author    David Sanchez <david38sanchez@gmail.com>
 * @copyright 2017 David Sanchez
 * @license   http://dwoo.org/LICENSE LGPLv3
 * @version   1.0.0
 * @date      2017-03-16
 * @link      http://symfony.dwoo.org/
 */

namespace Dwoo\SymfonyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds tagged dwoo.extension services to dwoo service.
 *
 * @package Dwoo\SymfonyBundle\DependencyInjection\Compiler
 */
class RegisterExtensionsPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('templating.engine.dwoo')) {
            return;
        }

        $definition = $container->getDefinition('templating.engine.dwoo');

        $calls = $definition->getMethodCalls();
        $definition->setMethodCalls([]);
        foreach ($container->findTaggedServiceIds('dwoo.plugin') as $id => $attributes) {
            $definition->addMethodCall('addPlugin', [new Reference($id)]);
        }
        $definition->setMethodCalls(array_merge($definition->getMethodCalls(), $calls));
    }
}