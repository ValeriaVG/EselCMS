<?php

use PHPUnit\Framework\TestCase;

class EselBasicModuleTest extends TestCase
{
    /**
     * God object Esel.
     *
     * @var Esel
     */
    private $Esel;

    /**
     * Module object.
     *
     * @var module
     */
    private $module;

    public function setUp()
    {
        require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.inc.php';
        $this->Esel = new Esel();
        $this->module = $this->Esel->module('EselBasicModule');
    }

    public function tearDown()
    {
        $this->Esel = null;
    }

    /**
     * @covers EselBasicModule::__construct
     */
    public function testInit()
    {
        $this->assertEquals($this->Esel, $this->module->Esel);
    }
}
