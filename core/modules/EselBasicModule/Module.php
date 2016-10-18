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
  }

  public static function sendWithSlash($text){
    return "/".$text;
  }
}
