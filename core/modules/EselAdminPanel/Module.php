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

            if ($thisFile >= $limit) {
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
            }
            $page->url = $page->makeUrl($dir.$file);
            array_push($list['pages'], $page);
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
        $pageData->template = EselPage::getTemplate($page);
        $pageData->blocks = EselPage::getPageBlocks($page);
        if (!empty($pageData->template)) {
            $pageData->fields = EselPage::getTemplateBlocks($pageData->template);
        }

        return $pageData;
    }
}
