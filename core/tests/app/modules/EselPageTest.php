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
    $this->assertEquals(array("textcontent"=>'<h1>Welcome to Docs!</h1>
        <a href="/docs/meet-modules/">Meet modules</a>'),EselPage::getPageBlocks("docs.html"));
  }


  public function testGetTemplateBlocks(){
    $this->assertEquals(array("content"=>"richText"),EselPage::getTemplateBlocks("base.twig"));
  }


}
