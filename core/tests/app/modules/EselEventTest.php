<?php

use PHPUnit\Framework\TestCase;

class EselEventTest extends TestCase
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


    }

    public function tearDown()
    {
        $this->Esel = null;
    }

    /**
     * @covers EselEvent::install
     * @covers EselEvent::addListener
     * @covers EselEvent::invoke
     */
    public function testCanAddEvent(){

      $this->module=$this->Esel->loadModule("EselEvent");
      Esel::for_table('events')->where_equal('event','TestEvent')->delete_many();
      EselEvent::addListener("TestEvent","EselBasicModule::AddNumbers");
      $event=Esel::for_table("events")->where('event','TestEvent')->findOne();
      $this->assertEquals($event->get("method"),"EselBasicModule::AddNumbers");
      $res=EselEvent::invoke("TestEvent",array(1,2));
      $this->assertEquals(3,$res);


    }

}
