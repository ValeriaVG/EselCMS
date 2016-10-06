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
        $this->assertEquals(false, $this->sl->_get('unknown'));
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
        $this->assertEquals(false, $this->sl->_post('unknown'));
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
        $this->assertEquals(false, $this->sl->_request('unknown'));
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
        $this->assertEquals(false, $this->sl->_cookie('unknown'));
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
        $this->assertEquals(false, $this->sl->_server('unknown'));
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
        $this->assertEquals(false, $this->sl->_session('unknown'));
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
        $this->assertEquals('301 to: /', $this->sl->route($indexUri));
        $noSlashUri = 'docs';
        $this->assertEquals('301 to: docs/', $this->sl->route($noSlashUri));
        $twoOrMoreSlashesUri = 'docs///////';
        $this->assertEquals('301 to: docs/', $this->sl->route($twoOrMoreSlashesUri));
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
}
