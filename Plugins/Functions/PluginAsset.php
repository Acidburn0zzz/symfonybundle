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
 * @date      2017-03-16
 * @link      http://symfony.dwoo.org/
 */

namespace Dwoo\SymfonyBundle\Plugins\Functions;

use Dwoo\Core;
use Dwoo\Plugin;
use Symfony\Component\Asset\Packages;

/**
 * Class PluginAsset
 *
 * @package Dwoo\SymfonyBundle\Plugins\Blocks
 */
class PluginAsset extends Plugin
{

    /** @var Packages */
    protected $packages;

    /**
     * PluginAsset constructor.
     *
     * @param Core     $core
     * @param Packages $packages
     */
    public function __construct(Core $core, Packages $packages)
    {
        $this->packages = $packages;
        parent::__construct($core);
    }

    /**
     * @param string $url
     *
     * @return mixed
     */
    public function process($url)
    {
        return $url;
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