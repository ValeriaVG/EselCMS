<?php

use PHPUnit\Framework\TestCase;

class EselMailerTest extends TestCase
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
     *
     * @covers EselMailer::__construct
     */
    public function testInit(){
      $this->module=$this->Esel->module("EselMailer");
      $this->assertTrue(class_exists("Swift_SmtpTransport"));
    }

}
