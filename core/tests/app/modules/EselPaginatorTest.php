<?php

use PHPUnit\Framework\TestCase;

class EselPaginatorTest extends TestCase
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
        $this->module = $this->Esel->module('EselPaginator');
    }

    public function tearDown()
    {
        $this->Esel = null;
    }

    public function testCanGetList(){
      $callback=function($iterator){
        return $iterator->getFilename();
      };
      $list=EselPaginator::getList(SL_PAGES,"*", 0, 0, $callback);
      $this->assertTrue(in_array("index.html",$list['items']));

      $listLimit=EselPaginator::getList(SL_PAGES,"*", 0, 1, $callback);
      $this->assertEquals(1,count($listLimit['items']));

      $listOut=EselPaginator::getList(SL_PAGES,"*", ($list['count']+1), 0, $callback);

      $this->assertEquals($list,$listOut);

      $listNone=EselPaginator::getList(SL_PAGES."__test/empty","*", 0, 0, $callback);
      $this->assertEquals(0,$listNone['count']);

      mkdir(SL_PAGES."__test/empty",0755);
      $listEmpty=EselPaginator::getList(SL_PAGES."__test/empty","*", 0, 0, $callback);

      $this->assertEquals(0,$listEmpty['count']);


      

    }

}
