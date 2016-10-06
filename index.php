<?php
require_once dirname(__FILE__).'/core/config.inc.php';
require_once SL_CORE."sl.php";
$sl=new sl();
echo $sl->handleRequest();
