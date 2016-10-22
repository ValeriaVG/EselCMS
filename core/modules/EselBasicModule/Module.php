<?php
class EselBasicModule extends EselModule{
  public static function sendGreeting(){
    return "Basic module sends its greeting!";
  }

  public static function addNumbers($a,$b){
    return $a+$b;
  }

  public static function usesGet(){
    if(!empty($_GET["name"])){
      return Esel::clear($_GET["name"]);
    }
    return "";
  }

  public static function sendWithSlash($text){
    return "/".$text;
  }

  public static function showStats(){
    $cpu=sys_getloadavg();
    $stats="<pre>CPU use:\n";
    $stats.="last min: ".$cpu[0];
    $stats.="\nlast 5 mins: ".$cpu[1];
    $stats.="\nlast 15 mins: ".$cpu[2];
    $stats.="\n\nRAM use: ".((memory_get_usage()/1024))." KB";
    $time_end=microtime(true);
    $stats.="\n\nExecution time:".(($time_end - SL_START)*1000)." ms</pre>";
    return $stats;
  }
}
