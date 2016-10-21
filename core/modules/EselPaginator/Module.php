<?php

class EselPaginator extends EselModule
{
    public static function getList($dir,$pattern, $start, $limit, $callback)
    {

        $list = array('count' => 0, 'items' => array());
        if (!is_dir($dir)) {
            return $list;
        }

        $iterator = new GlobIterator(Esel::fixPath($dir."/".$pattern), FilesystemIterator::SKIP_DOTS);
        try {
            $list['count'] = $iterator->count();
        } catch (Exception $e) {
            return $list;
        }
        if ($list['count'] == 0) {
            return $list;
        }
        if ($start >= $list['count']) {
            $start = 0;
        }
        $iterator->seek($start);

        $thisFile = 0;

        while ($iterator->valid()) {


            if (($thisFile >= $limit) && ($limit > 0)) {
                break;
            }

            $item = call_user_func_array($callback,array(&$iterator));
            if($item!=null){
              array_push($list['items'], $item);
            }
            ++$thisFile;
            $iterator->next();

        }

        return $list;
    }
}
