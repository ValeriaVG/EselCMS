<?php

class EselAdminPanel extends EselModule
{

    public static function beforeLoad()
    {
        if (true) {
            require_once SL_MODULES.'EselAdminPanel/EselPage.php';
        }
    }

    public static function isLoggedIn()
    {
        return true;
    }

    public static function getPagesList($dir = '')
    {
        self::beforeLoad();
        $path = SL_PAGES.$dir;
        $files = scandir($path);
        $list = array();
        foreach ($files as $file) {
            if (!in_array($file, array('.', '..'))&&(!preg_match("/^\.(.*)/",$file))) {
                $page = new EselPage();
                $page->name = $file;
                $page->folder = is_dir($path.$file);
                $page->path = $dir.$file;
                if ($page->folder) {
                    $page->path .= '/';
                }
                $page->url = $page->makeUrl($dir.$file);
                array_push($list, $page);
            }
        }

        return $list;
    }

    public static function getPageData($page=null){
      self::beforeLoad();
      if(empty($page)){
        if(empty(Esel::clear($_GET["page"]))){
          throw new Exception("You must define a page");
        }
        $page=Esel::clear($_GET["page"]);

      }
      $pageData=new stdClass();
      $pageData->template=EselPage::getTemplate($page);
      $pageData->blocks=EselPage::getPageBlocks($page);
      if(!empty($pageData->template)){
        $pageData->fields=EselPage::getTemplateBlocks($pageData->template);
      }
      return $pageData;
    }
}
