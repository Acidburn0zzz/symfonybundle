<?php
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
        foreach ($container->findTaggedServiceIds('dwoo.extension') as $id => $attributes) {
            $definition->addMethodCall('addExtension', [new Reference($id)]);
        }
        $definition->setMethodCalls(array_merge($definition->getMethodCalls(), $calls));
    }
}