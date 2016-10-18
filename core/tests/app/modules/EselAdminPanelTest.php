<?php

use PHPUnit\Framework\TestCase;

class EselAdminPanelTest extends TestCase
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
        $this->module = $this->Esel->module('EselAdminPanel');
    }

    public function tearDown()
    {
        $this->Esel = null;
    }
    /**
     * @covers EselAdminPanel::__construct
     */
    public function testInit()
    {

        $this->assertEquals($this->module->Esel, $this->Esel);
    }
    /**
     * @covers EselAdminPanel::beforeLoad
     */
    public function testBeforeLoad(){
      EselAdminPanel::beforeLoad();
      $this->assertTrue(class_exists("EselPage"));
    }
    /**
     * @covers EselAdminPanel::getPagesList
     */
    public function testCanGetPagesList()
    {
      $list = $this->module->getPagesList();
      $this->assertEquals('404.html', $list["pages"][0]->name);
    }

    /**
     * @covers EselAdminPanel::getPageData
     */
    public function testCanGetPageData()
    {
      $data = $this->module->getPageData('404.html');
      $this->assertEquals('docs.twig', $data->template);
    }
}
