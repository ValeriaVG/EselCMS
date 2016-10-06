<?php
class basicModule extends slModule{
  public static function sendGreeting(){
    return "Basic module sends its greeting!";
  }
  
  public static function addNumbers($a,$b){
    return $a+$b;
  }
}
