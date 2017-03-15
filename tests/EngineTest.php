<?php
/**
 * Copyright (c) 2017
 *
 * @category  Library
 * @package   Dwoo\SymfonBundle\tests
 * @author    David Sanchez <david38sanchez@gmail.com>
 * @copyright 2017 David Sanchez
 * @license   http://dwoo.org/LICENSE LGPLv3
 * @version   1.0.0
 * @date      2017-03-14
 * @link      http://symfony.dwoo.org/
 */

namespace Dwoo\SymfonyBundle\Tests;

/**
 * Class EngineTest
 *
 * @package Dwoo\SymfonyBundle\Tests
 */
class EngineTest extends BaseTests
{

    public function testGlobalVariables()
    {
        $engine = $this->getDwooEngine();
        $engine->addGlobal('global_variable', 'lorem ipsum');

        $this->assertEquals(['global_variable' => 'lorem ipsum',], $engine->getGlobals());
    }
}