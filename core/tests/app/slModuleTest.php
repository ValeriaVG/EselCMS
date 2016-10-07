<?php

use PHPUnit\Framework\TestCase;

class slModuleTest extends TestCase
{
    /**
     * God object sl.
     *
     * @var sl
     */
    private $sl;

    /**
     * Module object.
     *
     * @var module
     */
    private $module;

    public function setUp()
    {
        require_once dirname(dirname(dirname(__FILE__))).'/config.inc.php';
        $this->sl = new sl();
        $this->module = new slModule($this->sl);
    }

    public function tearDown()
    {
        $this->sl = null;
    }
    /**
     * @covers slModule::__construct
     */
    public function testCanCreateSlmoduleInstance()
    {
        $this->assertInstanceOf('slModule', $this->module);
        $this->assertEquals($this->sl, $this->module->sl);
    }
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method
     *
     * @return mixed Method return
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @covers slModule::calculateHash
     */
    public function testCanCalculateHash()
    {
        $basicHash = $this->invokeMethod($this->module, 'calculateHash');
        $this->assertNotEmpty($basicHash);
        $noHash = $this->invokeMethod($this->module, 'calculateHash', array('not existing folder'));
        $this->assertFalse($noHash);
        $subDirHash = $this->invokeMethod($this->module, 'calculateHash', array(SL_BASE));
        $this->assertNotEmpty($subDirHash);
    }

    /**
     * @covers slModule::saveHash
     */
    public function testCanSaveHash()
    {
        $dirname = SL_CORE.'hash/';
        if (is_dir($dirname)) {
            array_map('unlink', glob("$dirname/*"));
            rmdir($dirname);
        } else {
            mkdir($dirname, 0755);
        }
        $basicHashFile = $this->invokeMethod($this->module, 'saveHash');
        $this->assertTrue(file_exists($basicHashFile));
        $basicHashFileWithData = $this->invokeMethod($this->module, 'saveHash', array('test'));
        $this->assertEquals('test', file_get_contents($basicHashFileWithData));
    }
    /**
     * @covers slModule::setSafe
     *  @covers slModule::isSafe
     */
    public function testSetSafe()
    {
        $basicHashFile = slModule::setSafe('basicModule');
        $this->assertTrue(file_exists($basicHashFile));
        $this->assertTrue(slModule::isSafe('basicModule'));
    }

    /**
     * @covers slModule::setUnsafe
     * @expectedException        Exception
     * @expectedExceptionMessage basicModule is not installed
     */
    public function testSetUnsafe()
    {
        $basicHashFile = slModule::setUnsafe('basicModule');
        $this->assertFalse($this->assertTrue(slModule::isSafe('basicModule')));
        $this->assertEquals('basicModule is not installed', $e->getMessage());
        slModule::setSafe('basicModule');
    }
}
