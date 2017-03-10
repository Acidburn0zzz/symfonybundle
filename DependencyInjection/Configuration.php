<?php
namespace Dwoo\SymfonyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * This class contains the configuration information for the DwooBundle.
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @link    http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class
 * @package Dwoo\SymfonyBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     * Example configuration (YAML):
     * <code>
     * dwoo:
     *     # Dwoo options
     *     options:
     *         cache_dir:     %kernel.cache_dir%/dwoo/cache
     *         compile_dir:   %kernel.cache_dir%/dwoo/compile
     *         template_dir:  %kernel.root_dir%/Resources/views
     * </code>
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('dwoo');

        $this->addGlobalsSection($rootNode);
        $this->addDwooOptions($rootNode);

        return $treeBuilder;
    }

    /**
     * Dwoo options
     *
     * @param ArrayNodeDefinition $rootNode
     */
    protected function addDwooOptions(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('options')
                    ->canBeUnset()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('cache_dir')->defaultValue('%kernel.cache_dir%/dwoo/cache')->cannotBeEmpty()->end()
                        ->scalarNode('compile_dir')->defaultValue('%kernel.cache_dir%/dwoo/compile')->cannotBeEmpty()->end()
                        ->scalarNode('template_dir')->defaultValue('%kernel.root_dir%/Resources/views')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Template globals.
     *
     * @param ArrayNodeDefinition $rootNode
     */
    protected function addGlobalsSection(ArrayNodeDefinition $rootNode)
    {
       $rootNode
            ->children()
                ->arrayNode('globals')
                    ->useAttributeAsKey('key')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifTrue(function ($v) { return is_string($v) && '@' === substr($v, 0, 1); })
                            ->then(function ($v) { return array('id' => substr($v, 1), 'type' => 'service'); })
                        ->end()
                        ->beforeNormalization()
                            ->ifTrue(function ($v) {
                                if (is_array($v)) {
                                    $keys = array_keys($v);
                                    sort($keys);
                                    return $keys !== array('id', 'type') && $keys !== array('value');
                                }
                                return true;
                            })
                            ->then(function ($v) { return array('value' => $v); })
                        ->end()
                        ->children()
                            ->scalarNode('id')->end()
                            ->scalarNode('type')
                                ->validate()
                                    ->ifNotInArray(array('service'))
                                    ->thenInvalid('The %s type is not supported')
                                ->end()
                            ->end()
                            ->variableNode('value')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * WebProfiler
     *
     * @param ArrayNodeDefinition $rootNode
     */
    protected function addWebProfiler(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->booleanNode('web_profiler')
                    ->defaultFalse()
                ->end()
            ->end()
        ;

    }
}