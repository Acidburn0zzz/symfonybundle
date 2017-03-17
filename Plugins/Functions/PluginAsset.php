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
 * @date      2017-03-17
 * @link      http://symfony.dwoo.org/
 */

namespace Dwoo\SymfonyBundle\Plugins\Functions;

use Dwoo\Plugin;
use Dwoo\SymfonyBundle\Plugins\Plugins;
use Symfony\Component\Asset\Packages;

/**
 * Class PluginAsset
 *
 * @package Dwoo\SymfonyBundle\Plugins\Blocks
 */
class PluginAsset extends Plugin
{
    /** Use trait Plugins to have access to Container class */
    use Plugins;

    /**
     * Returns the public path of an asset.
     * Absolute paths (i.e. http://...) are returned unmodified.
     *
     * @param string $path        A public path
     * @param string $packageName The name of the asset package to use
     *
     * @return string A public path which takes into account the base path and URL path
     */
    public function process($path, $packageName = null)
    {
        /** @var Packages $packages */
        $packages = $this->container->get('assets.packages');

        return $packages->getUrl($path, $packageName);
    }

    /**
     * Get plugin's name.
     *
     * @return string
     */
    public function getName()
    {
        return 'asset';
    }
}