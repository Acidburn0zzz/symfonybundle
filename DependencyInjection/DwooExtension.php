<?php
namespace Dwoo\SymfonyBundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class DwooExtension
 *
 * @package Dwoo\SymfonyBundle
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
        $loader = new YamlFileLoader($container,
            new FileLocator(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Resources/config'));
        $loader->load('dwoo.yml');
    }
}