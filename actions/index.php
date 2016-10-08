<?php
require_once dirname(dirname(__FILE__)).'/core/config.inc.php';
require_once SL_CORE."classes/Esel.php";
$Esel=new Esel();
$webAction=$Esel->module("EselWebAction");
$webAction->handleRequest();
