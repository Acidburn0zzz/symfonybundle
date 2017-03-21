<?php
/**
 * Copyright (c) 2017
 *
 * @category  Library
 * @package   Dwoo\SymfonBundle\Plugins\Functions
 * @author    David Sanchez <david38sanchez@gmail.com>
 * @copyright 2017 David Sanchez
 * @license   http://dwoo.org/LICENSE LGPLv3
 * @version   1.0.0
 * @date      2017-03-21
 * @link      http://symfony.dwoo.org/
 */

namespace Dwoo\SymfonyBundle\Plugins\Functions;

use Dwoo\Plugin;
use Dwoo\SymfonyBundle\Plugins\Plugins;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Class PluginPath
 *
 * @package Dwoo\SymfonyBundle\Plugins\Functions
 */
class PluginPath extends Plugin
{
    /** Use trait Plugins to have access to Container class */
    use Plugins;

    /**
     * Returns the public path of an asset.
     * Absolute paths (i.e. http://...) are returned unmodified.
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return string A public path which takes into account the base path and URL path
     */
    public function process($name, $parameters = [])
    {
        /** @var Router $router */
        $router = $this->container->get('router');

        return $router->generate($name, $parameters, UrlGenerator::ABSOLUTE_PATH);
    }

    /**
     * Get plugin's name.
     *
     * @return string
     */
    public function getName()
    {
        return 'path';
    }
}