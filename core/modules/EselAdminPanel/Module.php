<?php
error_reporting(-1);
ini_set('display_errors', true);
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

    public static function getPagesList($dir = '', $start = 0, $limit = 20, $all = 0)
    {
        self::beforeLoad();
        $path = SL_PAGES.$dir;
        $list = array('count' => 0, 'pages' => array());
        $pattern = '/*.html';
        if ($all == 1) {
            $pattern = '/*';
        }

        $iterator = new GlobIterator($path.$pattern, FilesystemIterator::SKIP_DOTS);

        $list['count'] = $iterator->count();
        $iterator->seek($start);

        $thisFile = 0;

        while ($iterator->valid()) {
            $curr = $iterator->current();
            $file = $curr->getFilename();

            if (($thisFile >= $limit) && ($limit > 0)) {
                break;
            }

            ++$thisFile;
            $iterator->next();
            $page = new EselPage();

            $page->name = $file;
            $page->folder = is_dir($path.$file);
            $page->path = $dir.$file;
            if ($page->folder) {
                $page->path .= '/';
            } else {
                $m = array();
                if (preg_match('/{# @name (.+)? #}/', file_get_contents($path.$file), $m)) {
                    $page->name = $m[1];
                }
            }
            $page->url = $page->makeUrl($dir.$file);
            array_push($list['pages'], $page);
        }

        return $list;
    }

    public static function getTplList($dir = '', $start = 0, $limit = 0)
    {
        self::beforeLoad();
        $path = SL_TEMPLATES.$dir;
        $list = array('count' => 0, 'templates' => array());
        $pattern = '/*.twig';
        $iterator = new GlobIterator($path.$pattern, FilesystemIterator::SKIP_DOTS);

        $list['count'] = $iterator->count();
        $iterator->seek($start);

        $thisFile = 0;

        while ($iterator->valid()) {
            $curr = $iterator->current();
            $file = $curr->getFilename();

            if (($thisFile >= $limit) && ($limit > 0)) {
                break;
            }

            ++$thisFile;
            $iterator->next();
            $template = file_get_contents($path.$file);
            if (!preg_match('/{# @hidden #}/', $template)) {
                $tpl = new stdClass();
                $tpl->name = $file;
                $m = array();
                if (preg_match('/{# @name (.+)? #}/', $template, $m)) {
                    $tpl->name = $m[1];
                }
                $tpl->path = $dir.$file;
                array_push($list['templates'], $tpl);
            }
        }

        //scan subfolders
        foreach (glob($path.'/*', GLOB_ONLYDIR | GLOB_NOSORT) as $ndir) {
            $nlist = self::getTplList(str_replace($path, '', $ndir).'/', $start, $limit);
            $list['count'] += $nlist['count'];
            $list['templates'] = array_merge($nlist['templates'], $list['templates']);
        }

        return $list;
    }

    public static function getPageData($page = null)
    {
        self::beforeLoad();
        if (empty($page)) {
            if (empty(Esel::clear($_GET['page']))) {
                throw new Exception('You must define a page');
            }
            $page = Esel::clear($_GET['page']);
        }
        $pageData = new stdClass();
        if(isset($_GET['template'])){
          $pageData->template=Esel::clear($_GET['template']);
        }else{
          $pageData->template = EselPage::getTemplate($page);
        }

        $pageData->path = $page;
        $pageData->url = EselPage::makeUrl($page);
        $pageData->blocks = EselPage::getPageBlocks($page);
        $pageData->name=$page;
        if(is_dir(SL_PAGES.$page)){
          $pageData->url.="new-page/";
          $pageData->name="New Page";
          $pageData->new=true;
        }
        $m = array();
        if (preg_match('/{# @name (.+)? #}/', file_get_contents(SL_PAGES.$page), $m)) {
            $pageData->name = $m[1];
        }
        if (!empty($pageData->template)) {
            $pageData->fields = EselPage::getTemplateBlocks($pageData->template);
        }

        return $pageData;
    }
    public static function deletePage($path)
    {
        $fullpath=SL_PAGES.ltrim($path, '/');
      if(file_exists($fullpath)&&(!is_dir($fullpath))){
        if(!unlink($fullpath)){
          throw new Exception("Couldn't delete page ".$path);
        }
      }
    }

    public static function savePage($path, $template, $name, $blocks = array(), $fields = array(),$old_path="")
    {

        $path = ltrim($path, '/');
        if(empty($old_path)){
          $old_path=$path;
        }
        self::deletePage($old_path);
        //TODO: Save old one for backup
        $path = ltrim($path, '/');
        $page = '{% extends "'.$template.'" %}';
        if (!empty($name)) {
            $page .= '{# @name '.$name.' #}';
        }
        foreach ($blocks as $key => $value) {
            $page .= '{% block '.$key.' %}';
            $page .= html_entity_decode($value);
            $page .= '{% endblock %}';
        }

        foreach ($fields as $key => $value) {
            //TODO: Implement saving to database
        }
        $folder=preg_replace("/[^\/]+\.html$/","",SL_PAGES.$path);
        if(!is_dir($folder)){
          mkdir($folder,0755,true);
        }

        if (file_put_contents(SL_PAGES.$path, $page) === false) {
            throw new Exception("Couldn't save file ".SL_PAGES.$path." with ".$page);
        }

        return $page;
    }
}
