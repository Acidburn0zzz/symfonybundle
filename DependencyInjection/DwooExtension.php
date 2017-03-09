<?php
namespace Dwoo\SymfonyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * DwooExtension
 * This is the class that loads and manages DwooBundle configuration
 *
 * @link    http://symfony.com/doc/current/cookbook/bundles/extension.html
 * @package Dwoo\SymfonyBundle\DependencyInjection
 */
class DwooExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        /**
         * Load Yaml dwoo config file
         */
        $loader = new YamlFileLoader($container,
            new FileLocator(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Resources/config'));
        $loader->load('dwoo.yml');

        /**
         * Load configuration
         */
        $configuration    = new Configuration();
        $config           = $this->processConfiguration($configuration, $configs);
        $engineDefinition = $container->getDefinition('templating.engine.dwoo');

        if (!empty($config['globals'])) {
            foreach ($config['globals'] as $key => $global) {
                if (isset($global['type']) && 'service' === $global['type']) {
                    $engineDefinition->addMethodCall('addGlobal', [$key, new Reference($global['id'])]);
                } else {
                    $engineDefinition->addMethodCall('addGlobal', [$key, $global['value']]);
                }
            }
        }

        $container->setParameter('dwoo.options', $config['options']);
    }
}