<?php
use PHPUnit\Framework\TestCase;
require_once SL_MODULES."EselAdminPanel/EselPage.php";

class EselPageTest extends TestCase
{
  /**
 * @covers EselPage::makeUrl
 * @expectedException        Exception
 * @expectedExceptionMessage Page doesn't exist
 */
  public function testCanMakeUrl(){
    $this->assertEquals("/docs/",EselPage::makeUrl("docs.html"));
    $this->assertEquals("/docs/",EselPage::makeUrl("/docs.html"));
    $this->assertEquals("/docs/meet-modules/",EselPage::makeUrl("/docs/meet-modules.html"));
    $this->assertEquals("/docs/meet-modules/",EselPage::makeUrl("docs/meet-modules.html"));
    $this->assertEquals("/",EselPage::makeUrl("/index.html"));
    $this->assertEquals("/404/",EselPage::makeUrl("404.html"));
    $this->assertEquals("/404/",EselPage::makeUrl("/404.html"));
    EselPage::makeUrl("some-crappp");
    $this->assertEquals("Page doesn't exist",$e->getMessage());
  }


  public function testGetPageTemplate(){
    $this->assertEquals("docs.twig",EselPage::getTemplate("404.html"));
    $this->assertEquals("base.twig",EselPage::getTemplate("index.html"));
  }

  public function testGetPageBlocks(){
    $this->assertEquals(array("content"=>'<p>TEST PAGE</p>'),EselPage::getPageBlocks("__test/__test.html"));
  }


  public function testGetTemplateBlocks(){
    $block=EselPage::getTemplateBlocks("docs.twig");
    $this->assertEquals("richText",$block['textcontent']->type);
  }


}
