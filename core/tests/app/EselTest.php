<?php

use PHPUnit\Framework\TestCase;

class EselTest extends TestCase
{
    /**
     * God object Esel.
     *
     * @var Esel
     */
    private $Esel;

    public function setUp()
    {
        require_once dirname(dirname(dirname(__FILE__))).'/config.inc.php';
        $this->Esel = new Esel();
    }

    public function tearDown()
    {
        $this->Esel = null;
    }
    /**
     * Test constructor.
     *
     * @covers Esel::__construct
     * @covers Esel::init
     */
    public function testCanCreateSlInstance()
    {
        $this->assertInstanceOf('Esel', $this->Esel);
    }

    /**
     * Test renderer.
     *
     * @covers EselRenderer::render
     */
    public function testCanRenderTwigTemplate()
    {

        //{{ 2 + 3}}!
        $templateFile = 'test.twig';
        $this->assertEquals('5!', trim($this->Esel->renderer->render($templateFile)));
    }

    /**
     * Test sanitizer
     *
     * @covers Esel::clear
     */
    public function testCanGetGlobal()
    {
        $_GET['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->Esel->clear($_GET['test']));
        $get = $this->Esel->clear($_GET);
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $get['test']);

    }
    /**
     * Test router.
     *
     * @covers Esel::route
     * @covers Esel::respondWithCode
     * @runInSeparateProcess
     */
    public function testCanRoute()
    {
        $baseUri = '/';
        $this->assertEquals('index.html', $this->Esel->route($baseUri));
        $pageUri = 'docs/meet-modules/';
        $this->assertEquals('docs/meet-modules.html', $this->Esel->route($pageUri));
        $dirUri = 'docs/';
        $this->assertEquals('docs.html', $this->Esel->route($dirUri));
        $indexUri = 'index';
        $this->assertEquals('index.html', $this->Esel->route($indexUri));
        $noSlashUri = 'docs';
        $this->assertEquals('docs.html', $this->Esel->route($noSlashUri));
        $twoOrMoreSlashesUri = 'docs///////';
        $this->assertEquals('docs.html', $this->Esel->route($twoOrMoreSlashesUri));
        $badUri = 'i-dont-exist-for-sure-cause-i-am-just-too-bad-like-justin-bieber-song/';
        $pageNotFound = $this->Esel->route($badUri);
        $this->assertEquals('404.html', $pageNotFound);
    }

    /**
     * Test request handler.
     *
     * @covers Esel::handleRequest
     */
    public function testCanHandleRequest()
    {
        $_GET['uri'] = 'docs/';
        preg_match('/<h1[^>]*>([\s\S]*?)<\/h1[^>]*>/i', $this->Esel->handleRequest(), $h1);
        $this->assertEquals('<h1>Welcome to Docs!</h1>', $h1[0]);

        $_GET['uri'] = '';
        preg_match('/<h1[^>]*>([\s\S]*?)<\/h1[^>]*>/i', $this->Esel->handleRequest(), $h1);
        $this->assertEquals('<h1>Congratulations! It works!</h1>', $h1[0]);
    }

    /**
     * Test module loading.
     *
     * @covers Esel::loadModule
     * @covers EselModule::isSafe
     * @expectedException        Exception
     * @expectedExceptionMessage Crapp is not installed
     */
    public function testCanLoadModule()
    {
        EselModule::setSafe('EselBasicModule');
        $moduleName = 'EselBasicModule';
        $this->Esel->loadModule($moduleName);
        $this->assertTrue(class_exists($moduleName));
        $this->Esel->loadModule('Crapp');
        $this->assertEquals('Crapp is not installed', $e->getMessage());
    }

        /**
         * Test module object.
         *
         * @covers Esel::module
         */
        public function testCanModule()
        {
            $moduleName = 'EselBasicModule';
            $EselBasicModule = $this->Esel->module($moduleName);
            $this->assertInstanceOf($moduleName, $EselBasicModule);
            $this->assertEquals($this->Esel, $EselBasicModule->Esel);
        }
        /**
         * Tesing data adding.
         *
         * @covers EselRenderer::setData
         * @covers EselRenderer::getData
         */
        public function testCanSetData()
        {
          $this->Esel->renderer->setData("test","data");
          $data=$this->Esel->renderer->getData("test");
          $this->assertEquals("data",$data);
          $this->assertEquals(array("test"=>"data"),$this->Esel->renderer->getData());

        }


        public function testCanFixPath(){
          $this->assertEquals("/path/to/a/file/",$this->Esel->fixPath("//path/to///a/file//"));
        }


        public function testCanDB(){
          $this->Esel->connect();
          ORM::raw_execute("DROP TABLE IF EXISTS sl_created_table");
          $this->Esel->create_table("created_table",array("data"=>"VARCHAR(25) NULL DEFAULT NULL"));
          $test=$this->Esel->for_table("created_table")->create();
          $test->data="works";
          $test->save();
          $res=$this->Esel->for_table("created_table")->find_one();
          $this->assertEquals("works",$res->data);
          try{
            $this->Esel->create_table("crap_table",array());
          }catch(Exception $e){
            $this->assertEquals("Cannot create table '.crap_table.'- no columns provived",$e->getMessage());
          }

        }


}
