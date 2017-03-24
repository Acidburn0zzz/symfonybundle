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
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class PluginTrans
 *
 * @package Dwoo\SymfonyBundle\Plugins\Blocks
 */
class PluginTrans extends BlockPlugin
{
    /** Use trait Plugins to have access to Container class */
    use Plugins;

    /** Properties */
    protected $parameters = [];
    protected $domain;
    protected $locale;

    /**
     * Parameters
     *
     * @param array       $parameters
     * @param string|null $domain
     * @param string|null $locale
     */
    public function init(array $parameters = [], $domain = null, $locale = null)
    {
        $this->parameters = $parameters;
        $this->domain     = $domain;
        $this->locale     = $locale;
    }

    /**
     * @see TranslatorInterface::trans()
     * @return string
     */
    public function process()
    {
        /** @var Translator $translator */
        $translator = $this->getContainer()->get('translator');

        return $translator->trans($this->buffer, $this->parameters, $this->domain, $this->locale);
    }

    /**
     * Get plugin's name.
     *
     * @return string
     */
    public function getName()
    {
        return 'trans';
    }
}