<?php
class EselBasicModule extends EselModule{
  public static function sendGreeting(){
    return "Basic module sends its greeting!";
  }

  public static function addNumbers($a,$b){
    return $a+$b;
  }

  public static function usesGet(){
    return Esel::sga(Esel::GET,"name");
  }

  public static function sendWithSlash($text){
    return "/".$text;
  }
}
