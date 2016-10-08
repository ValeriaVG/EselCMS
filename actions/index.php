<?php
require_once dirname(dirname(__FILE__)).'/core/config.inc.php';
require_once SL_CORE."classes/sl.php";
$sl=new sl();
$webAction=$sl->module("webAction");
$webAction->handleRequest();
