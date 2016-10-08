<?php
class BasicEselModule extends EselModule{
  public static function sendGreeting(){
    return "Basic module sends its greeting!";
  }

  public static function addNumbers($a,$b){
    return $a+$b;
  }

  public static function usesGet(){
    return Esel::sga(Esel::GET,"name");
  }
}
