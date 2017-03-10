<?php
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