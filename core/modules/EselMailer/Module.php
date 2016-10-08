<?php
class EselMailer extends EselModule{
  public function __construct(){    
    require_once SL_CORE."vendor/swiftmailer/swiftmailer/lib/classes/Swift.php";
  }
}
