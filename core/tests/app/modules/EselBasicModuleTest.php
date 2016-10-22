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

    public function testCanDoThings(){
      $this->assertEquals("Basic module sends its greeting!",EselBasicModule::sendGreeting());
      $this->assertEquals(5,EselBasicModule::addNumbers(2,3));
      $this->assertEquals("",EselBasicModule::usesGet());
      $_GET['name']="get";
      $this->assertEquals("get",EselBasicModule::usesGet());
      $this->assertEquals("/get",EselBasicModule::sendWithSlash("get"));
      $this->assertTrue(preg_match("/CPU use/",EselBasicModule::showStats())==1);
    }
}
