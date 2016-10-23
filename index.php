<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once dirname(__FILE__).'/core/config.inc.php';
require_once SL_CORE."classes/Esel.php";
$Esel=new Esel();
echo $Esel->handleRequest();
