<?php
/*
*  This file is for php built in server routing
*/
  if (preg_match('/\.(?:png|jpg|jpeg|gif|html|css|js|pdf)$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    $_GET["uri"]=htmlspecialchars($_SERVER["REQUEST_URI"]);
    include __DIR__ . '/index.php';
}
