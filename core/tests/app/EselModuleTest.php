<?php

use PHPUnit\Framework\TestCase;

class EselModuleTest extends TestCase
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
        require_once dirname(dirname(dirname(__FILE__))).'/config.inc.php';
        $this->Esel = new Esel();
        $this->module = new EselModule($this->Esel);
    }

    public function tearDown()
    {
        $this->Esel = null;
    }
    /**
     * @covers EselModule::__construct
     */
    public function testCanCreateSlmoduleInstance()
    {
        $this->assertInstanceOf('EselModule', $this->module);
        $this->assertEquals($this->Esel, $this->module->Esel);
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
     * @covers EselModule::calculateHash
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
     * @covers EselModule::saveHash
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
        $hashFile = $this->invokeMethod($this->module, 'saveHash');
        $this->assertTrue(file_exists($hashFile));
        $hashWithData = $this->invokeMethod($this->module, 'saveHash', array('test'));
        $this->assertEquals('test', file_get_contents($hashWithData));
    }
    /**
     * @covers EselModule::setSafe
     *  @covers EselModule::isSafe
     */
    public function testSetSafe()
    {
        $basicHashFile = EselModule::setSafe('EselBasicModule');
        $this->assertTrue(file_exists($basicHashFile));
        $this->assertTrue(EselModule::isSafe('EselBasicModule'));
    }

    /**
     * @covers EselModule::setUnsafe
     * @expectedException        Exception
     * @expectedExceptionMessage EselBasicModule is not installed
     */
    public function testSetUnsafe()
    {
        $basicHashFile = EselModule::setUnsafe('EselBasicModule');
        $this->assertFalse($this->assertTrue(EselModule::isSafe('EselBasicModule')));
        $this->assertEquals('EselBasicModule is not installed', $e->getMessage());
        EselModule::setSafe('EselBasicModule');
    }
}
