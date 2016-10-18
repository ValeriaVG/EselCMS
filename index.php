<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once dirname(__FILE__).'/core/config.inc.php';
require_once SL_CORE."classes/Esel.php";
$Esel=new Esel();
echo $Esel->handleRequest();
