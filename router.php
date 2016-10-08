<?php
/*
*  This file is for php built in server routing
*/
define('PHPUNIT_RUNNING', 0);
  if (preg_match('/\.(?:png|jpg|jpeg|gif|html|css|js|pdf|woff2|woff|ttf|ico)$/', $_SERVER['REQUEST_URI'])) {

      return false;
  } else {
      $_GET['uri'] = preg_replace('/\?(.*)$/', '', $_SERVER['REQUEST_URI']);

      if (preg_match('/^\/actions\//', $_SERVER['REQUEST_URI'])) {
          include dirname(__FILE__).'/actions/'.'index.php';
      }

      include dirname(__FILE__).'/index.php';
  }
