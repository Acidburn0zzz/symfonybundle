<?php
/**
 * Copyright (c) 2017
 *
 * @category  Library
 * @package   Dwoo\SymfonBundle\Plugins
 * @author    David Sanchez <david38sanchez@gmail.com>
 * @copyright 2017 David Sanchez
 * @license   http://dwoo.org/LICENSE LGPLv3
 * @version   1.0.0
 * @date      2017-03-17
 * @link      http://symfony.dwoo.org/
 */

namespace Dwoo\SymfonyBundle\Plugins;

use Dwoo\Core;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class Plugins
 *
 * @package Dwoo\SymfonyBundle\Plugins
 */
trait Plugins
{

    /** @var Container */
    protected $container;

    /**
     * Plugins constructor
     *
     * @param Core $core
     */
    public function __construct(Core $core)
    {
        parent::__construct($core);

        /** @var Container $container */
        $this->container = $this->core->getGlobals()['container'];
    }
}