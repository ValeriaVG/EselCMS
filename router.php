<?php
/*
*  This file is for php built in server routing
*/


define ('PHPUNIT_RUNNING', 0);
  if (preg_match('/\.(?:png|jpg|jpeg|gif|html|css|js|pdf|woff2|woff|ttf|ico)$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
$_GET["uri"]=htmlspecialchars(preg_replace('/\?(.*)$/',"",$_SERVER["REQUEST_URI"]));
  if(preg_match('/^\/actions\//', $_SERVER["REQUEST_URI"])){
    include __DIR__."/actions/". 'index.php';
  }

    include __DIR__ . '/index.php';
}
