<?php
/*
* WARNING: This is for DEVELOPING ONLY!
* Keeping this file in production enviroment can be a huge implication
*/

require_once dirname(dirname(__FILE__)).'/core/config.inc.php';
require_once SL_CORE."classes/slmodule.php";
$dir=str_replace("/","\\",(str_replace("//","/",SL_MODULES)));
$modules=scandir($dir);
printf("scanning ".$dir."\n");
foreach($modules as $module){

    printf("Signing ".$module.": ".slModule::setSafe($module)."\n");

}
 ?>
