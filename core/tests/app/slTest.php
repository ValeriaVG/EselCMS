<?php

use PHPUnit\Framework\TestCase;

class slTest extends TestCase
{
    /**
     * God object sl.
     *
     * @var sl
     */
    private $sl;

    public function setUp()
    {
        require_once dirname(dirname(dirname(__FILE__))).'/config.inc.php';
        $this->sl = new sl();
    }

    public function tearDown()
    {
        $this->sl = null;
    }
    /**
     * Test constructor.
     *
     * @covers sl::__construct
     * @covers sl::init
     */
    public function testCanCreateSlInstance()
    {
        $this->assertInstanceOf('sl', $this->sl);
    }

    /**
     * Test renderer.
     *
     * @covers sl::render
     */
    public function testCanRenderTwigTemplate()
    {

        //{{ 2 + 3}}!
        $templateFile = 'test.twig';
        $this->assertEquals('5!', trim($this->sl->render($templateFile)));
    }

    /**
     * Test superglobal getter.
     *
     * @covers sl::g
     */
    public function testCanGetGlobal()
    {
        $_GET['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->g(sl::GET,'test'));
        $this->assertEquals('', $this->sl->g(sl::GET,'unknown'));
        $get = $this->sl->g(sl::GET);
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $get['test']);
        $_POST['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->g(sl::POST,'test'));
        $this->assertEquals('', $this->sl->g(sl::POST,'unknown'));
        $post = $this->sl->g(sl::POST);
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $post['test']);
        $_REQUEST['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->g(sl::REQUEST,'test'));
        $this->assertEquals('', $this->sl->g(sl::REQUEST,'unknown'));
        $request = $this->sl->g(sl::REQUEST);
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $request['test']);
        $_COOKIE['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->g(sl::COOKIE,'test'));
        $this->assertEquals('', $this->sl->g(sl::COOKIE,'unknown'));
        $cookie = $this->sl->g(sl::COOKIE);
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $cookie['test']);
        $_SERVER['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->g(sl::SERVER,'test'));
        $this->assertEquals('', $this->sl->g(sl::SERVER,'unknown'));
        $_SESSION['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->g(sl::SESSION,'test'));
        $this->assertEquals('', $this->sl->g(sl::SESSION,'unknown'));
        $session = $this->sl->g(sl::SESSION);
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $session['test']);
        $this->assertEquals(null, $this->sl->g(-1,'unknown'));
    }
    /**
     * Test router.
     *
     * @covers sl::route
     * @covers sl::respondWithCode
     * @runInSeparateProcess
     */
    public function testCanRoute()
    {
        $baseUri = '/';
        $this->assertEquals('index.html', $this->sl->route($baseUri));
        $pageUri = 'docs/meet-modules/';
        $this->assertEquals('docs/meet-modules.html', $this->sl->route($pageUri));
        $dirUri = 'docs/';
        $this->assertEquals('docs/index.html', $this->sl->route($dirUri));
        $indexUri = 'index';
        $this->assertEquals('index.html', $this->sl->route($indexUri));
        $noSlashUri = 'docs';
        $this->assertEquals('docs/index.html', $this->sl->route($noSlashUri));
        $twoOrMoreSlashesUri = 'docs///////';
        $this->assertEquals('docs/index.html', $this->sl->route($twoOrMoreSlashesUri));
        $badUri = 'i-dont-exist-for-sure-cause-i-am-just-too-bad-like-justin-bieber-song/';
        $pageNotFound = $this->sl->route($badUri);
        $this->assertEquals('404.html', $pageNotFound);
    }

    /**
     * Test request handler.
     *
     * @covers sl::handleRequest
     */
    public function testCanHandleRequest()
    {
        $_GET['uri'] = 'docs/';
        preg_match('/<h1[^>]*>([\s\S]*?)<\/h1[^>]*>/i', $this->sl->handleRequest(), $h1);
        $this->assertEquals('<h1>Welcome to Docs!</h1>', $h1[0]);
    }

    /**
     * Test module loading.
     *
     * @covers sl::loadModule
     * @covers slModule::isSafe
     * @expectedException        Exception
     * @expectedExceptionMessage Crapp is not installed
     */
    public function testCanLoadModule()
    {
        slModule::setSafe('basicModule');
        $moduleName = 'basicModule';
        $this->sl->loadModule($moduleName);
        $this->assertTrue(class_exists($moduleName));
        $this->sl->loadModule('Crapp');
        $this->assertEquals('Crapp is not installed', $e->getMessage());
    }

        /**
         * Test module object.
         *
         * @covers sl::module
         */
        public function testCanModule()
        {
            $moduleName = 'basicModule';
            $basicModule = $this->sl->module($moduleName);
            $this->assertInstanceOf($moduleName, $basicModule);
            $this->assertEquals($this->sl, $basicModule->sl);
        }
        /**
         * Tesing data adding.
         *
         * @covers sl::addData
         * @covers sl::getData
         */
        public function testCanAddData()
        {
          $this->sl->addData("test","data");
          $data=$this->sl->getData("test");
          $this->assertEquals("data",$data);
          $this->assertEquals(array("test"=>"data"),$this->sl->getData());

        }
        /**
         * Testing database connection
         * @covers sl::db
         */
        public function testCanDB(){
          $data=$this->sl->db("test")->find_one()->data;
          $this->assertEquals("test",$data);
        }
}
