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
        if(!is_dir(SL_PAGES."__test")){
          mkdir(SL_PAGES."__test",0755);
        }
    }

    public function tearDown()
    {
        $this->Esel = null;
    }

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


    public function testCanSavePage(){
      //savePage($path,$template,$name,$fields=array(),$blocks=array())
      $this->assertEquals(EselAdminPanel::savePage('/__test/__test.html','base.twig','Test Page'),file_get_contents(SL_PAGES.'__test/__test.html'));
    }

    /**
     * @covers EselAdminPanel::getPagesList
     */
    public function testCanGetPagesList()
    {
      $list = $this->module->getPagesList('__test/');
      $this->assertEquals('Test Page', $list["pages"][0]->name);
    }

    /**
     * @covers EselAdminPanel::getTplList
     */
    public function testCanGetTplList()
    {
      $list = $this->module->getTplList();
      $this->assertEquals('Base Layout', $list["templates"][0]->name);
      $this->assertEquals('base.twig', $list["templates"][0]->path);
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
