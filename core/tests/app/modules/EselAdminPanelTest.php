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

    public function testInit()
    {
        $this->assertEquals($this->module->Esel, $this->Esel);
    }

    /**
     * @covers EselAdminPanel::beforeLoad
     */
    public function testBeforeLoad()
    {
      try{
        EselAdminPanel::beforeLoad();
      }catch(Exception $e){
        $this->assertEquals("Operation not permitted",$e->getMessage());
      }
    }

    private function logIn(){
      $_POST['login']="admin";
      $_POST['password']="password";
      EselAdminPanel::LogIn();
    }

    public function testCanLogIn()
    {
      $this->logIn();
        $this->assertTrue(EselAdminPanel::isLoggedIn());
    }


    public function testCanSavePage()
    {
      $this->logIn();

        //savePage($path,$template,$name,$fields=array(),$blocks=array())
      $this->assertEquals(EselAdminPanel::savePage('/__test/__test.html', 'base.twig', 'Test Page', array('content' => '<p>TEST PAGE</p>')), file_get_contents(SL_PAGES.'__test/__test.html'));
      $this->assertEquals(EselAdminPanel::savePage('/__test/test.htm', 'docs.twig', 'Draft Page', array('testcontent' => '<p>Draft</p>')), file_get_contents(SL_PAGES.'__test/test.htm'));
    }

    /**
     * @covers EselAdminPanel::getPagesList
     */
    public function testCanGetPagesList()
    {

        $list = EselAdminPanel::getPagesList('__test/');
        $this->assertEquals('Test Page', $list['items'][0]->name);

        $fullList = EselAdminPanel::getPagesList('__test/', 0, 10, 1);
        $this->assertEquals(2, $fullList['count']);
    }

    /**
     * @covers EselAdminPanel::getTplList
     */
    public function testCanGetTplList()
    {
      $this->logIn();
        $list = EselAdminPanel::getTplList();
        $this->assertEquals('Base Layout', $list['items'][0]->name);
        $this->assertEquals('base.twig', $list['items'][0]->path);
    }

    /**
     * @covers EselAdminPanel::getPageData
     */
    public function testCanGetPageData()
    {
      $this->logIn();
        $data = EselAdminPanel::getPageData('__test/__test.html');
        $this->assertEquals('base.twig', $data->template);

        try{
          $dataNully = EselAdminPanel::getPageData();
          $this->fail("Page was not defined but exception was not raised!");
        }catch(Exception $e){
          $this->assertEquals('You must define a page', $e->getMessage());
        }
        $_GET['template']="docs.twig";
        $_GET['page']='__test/__test.html';
        $dataEmpty = EselAdminPanel::getPageData();
        $this->assertEquals('docs.twig', $dataEmpty->template);
        $this->assertEquals('/__test/__test/', $dataEmpty->url);

        $dataNew = EselAdminPanel::getPageData('__test/');
        $this->assertEquals('/__test/new-page/', $dataNew->url);
        $this->assertTrue($dataNew->new);


    }

    public function testCanDeletePage()
    {
      $this->logIn();
        EselAdminPanel::savePage('__test/i/exist.html');
        EselAdminPanel::deletePage('__test/i/exist.html');
        $this->assertFalse(file_exists(SL_PAGES.'__test/i/exist.html'));
        try{
          EselAdminPanel::deletePage('__test/');
          $this->fail("Folder was deleted!");
        }catch(Exception $e){
          $this->assertEquals('Couldn\'t delete page __test/', $e->getMessage());
        }
    }



    public function testCanGetModulesList(){
      $this->logIn();
      $modules=EselAdminPanel::getModulesList();
      foreach($modules['modules'] as $module ){
        if($module->name=="EselAdminPanel"){
          $this->assertTrue($module->status);
          return true;
        }
      }
      $this->fail("Couldn't find this module in the list!");

    }
}
