<?php
define ('PHPUNIT_RUNNING', 1);
require_once(dirname(dirname(dirname(dirname(__FILE__))))."/tools/signthemall.php");
require_once(dirname(dirname(dirname(__FILE__)))."/classes/Esel.php");
require_once(dirname(dirname(dirname(__FILE__)))."/classes/EselModule.php");//Strange, windows doesnt care about filename case
$dir = SL_PAGES.'__test';
if (!is_dir($dir)) {
    mkdir(SL_PAGES.'__test', 0777);
}
$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
$files = new RecursiveIteratorIterator($it,
     RecursiveIteratorIterator::CHILD_FIRST);
foreach ($files as $file) {
    if ($file->isDir()) {
        rmdir($file->getRealPath());
    } else {
        unlink($file->getRealPath());
    }
}



 ?>
