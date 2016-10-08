<?php
require_once dirname(__FILE__).'/core/config.inc.php';
require_once SL_CORE."classes/sl.php";
$sl=new sl();
echo $sl->handleRequest();
