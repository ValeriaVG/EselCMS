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
     * @covers sl::_get
     */
    public function testCanGetGet()
    {
        $_GET['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->_get('test'));
        $this->assertEquals('', $this->sl->_get('unknown'));
        $get = $this->sl->_get();
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $get['test']);
    }

    /**
     * Test superglobal getter.
     *
     * @covers sl::_post
     */
    public function testCanGetPost()
    {
        $_POST['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->_post('test'));
        $this->assertEquals('', $this->sl->_post('unknown'));
        $post = $this->sl->_post();
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $post['test']);
    }

    /**
     * Test superglobal getter.
     *
     * @covers sl::_request
     */
    public function testCanGetRequest()
    {
        $_REQUEST['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->_request('test'));
        $this->assertEquals('', $this->sl->_request('unknown'));
        $request = $this->sl->_request();
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $request['test']);
    }

    /**
     * Test superglobal getter.
     *
     * @covers sl::_cookie
     */
    public function testCanGetCookie()
    {
        $_COOKIE['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->_cookie('test'));
        $this->assertEquals('', $this->sl->_cookie('unknown'));
        $cookie = $this->sl->_cookie();
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $cookie['test']);
    }

    /**
     * Test superglobal getter.
     *
     * @covers sl::_server
     */
    public function testCanGetServer()
    {
        $_SERVER['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->_server('test'));
        $this->assertEquals('', $this->sl->_server('unknown'));
    }

    /**
     * Test superglobal getter.
     *
     * @covers sl::_session
     */
    public function testCanGetSession()
    {
        $_SESSION['test'] = '<script>alert("test");</script>';
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $this->sl->_session('test'));
        $this->assertEquals('', $this->sl->_session('unknown'));
        $session = $this->sl->_session();
        $this->assertEquals('&lt;script&gt;alert(&quot;test&quot;);&lt;/script&gt;', $session['test']);
    }
    /**
     * Test router.
     *
     * @covers sl::route
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
