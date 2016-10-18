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
     * @covers Esel::render
     */
    public function testCanRenderTwigTemplate()
    {

        //{{ 2 + 3}}!
        $templateFile = 'test.twig';
        $this->assertEquals('5!', trim($this->Esel->render($templateFile)));
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
        $this->assertEquals('docs/index.html', $this->Esel->route($dirUri));
        $indexUri = 'index';
        $this->assertEquals('index.html', $this->Esel->route($indexUri));
        $noSlashUri = 'docs';
        $this->assertEquals('docs/index.html', $this->Esel->route($noSlashUri));
        $twoOrMoreSlashesUri = 'docs///////';
        $this->assertEquals('docs/index.html', $this->Esel->route($twoOrMoreSlashesUri));
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
         * @covers Esel::addData
         * @covers Esel::getData
         */
        public function testCanAddData()
        {
          $this->Esel->addData("test","data");
          $data=$this->Esel->getData("test");
          $this->assertEquals("data",$data);
          $this->assertEquals(array("test"=>"data"),$this->Esel->getData());

        }
        /**
         * Testing database connection
         * @covers Esel::for_table
         */
        public function testCanDB(){
          $data=$this->Esel->for_table("test")->find_one()->data;
          $this->assertEquals("test",$data);
        }
}
