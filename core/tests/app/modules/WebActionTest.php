<?php

use PHPUnit\Framework\TestCase;

class WebActionTest extends TestCase
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
        $this->module=$this->Esel->module("WebAction");

    }

    public function tearDown()
    {
        $this->Esel = null;
    }

    /**
     *
     * @covers WebAction::handleRequest
     */
    public function testCanHandleRequest(){
      $_GET['uri']="/actions/BasicEselModule/sendGreeting";
      $output=$this->module->handleRequest();
      $this->assertEquals('{"success":true,"data":"Basic module sends its greeting!"}',$output);

      $_GET['uri']="/actions/Crap";
      $output=$this->module->handleRequest();
      $this->assertEquals('{"success":false,"message":"No action specified"}',$output);

      try{
        $_GET['uri']="/actions/NoModule/OrAction";
        $output=$this->module->handleRequest();
      }catch(Exception $e){
        $this->assertEquals('{"success":false,"message":"Module NoModule is not installed"}',$e->getMessage());
      }
    }

}
