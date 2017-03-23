<?php
/**
 * Copyright (c) 2017
 *
 * @category  Library
 * @package   Dwoo\SymfonBundle\Plugins\Blocks
 * @author    David Sanchez <david38sanchez@gmail.com>
 * @copyright 2017 David Sanchez
 * @license   http://dwoo.org/LICENSE LGPLv3
 * @version   1.0.0
 * @date      2017-03-23
 * @link      http://symfony.dwoo.org/
 */

namespace Dwoo\SymfonyBundle\Plugins\Blocks;

use Dwoo\Block\Plugin as BlockPlugin;
use Dwoo\SymfonyBundle\Plugins\Plugins;
use Assetic\Factory\AssetFactory;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class PluginJavascripts
 *
 * @package Dwoo\SymfonyBundle\Plugins\Blocks
 */
class PluginJavascripts extends BlockPlugin
{
    /** Use trait Plugins to have access to Container class */
    use Plugins;

    protected $inputs = [];

    public function init(array $inputs, array $filters = [], array $rest = [])
    {
        $this->inputs = $inputs;
    }

    public function process()
    {
        $output = [];

        /** @var AssetFactory $factory */
        $factory = $this->container->get('assetic.asset_factory');

        foreach ($this->inputs as $input) {
            $asset = $factory->createAsset([$input]);
            $url = $asset->getTargetPath();
            var_dump($this->container->get('assets.packages')->getUrl($url));exit;
            //            $output[] = sprintf($this->buffer, $input);
        }

        return implode("", $output);
    }

    /**
     * Get plugin's name.
     *
     * @return string
     */
    public function getName()
    {
        return 'javascripts';
    }
}