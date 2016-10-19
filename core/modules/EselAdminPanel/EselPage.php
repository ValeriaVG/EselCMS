<?php
class EselPage{
  public $name;
  public $folder;
  public $url;
  public $new=false;

  public static function makeUrl($relativePath){
    if(!file_exists(SL_PAGES.$relativePath)){
      throw new Exception("Page doesn't exist at ".$relativePath);
    }
    $url=preg_replace("/(\/|)((index|)\.html)/","",$relativePath);
    return str_replace("//","/","/".$url."/");
  }

  public static function getTemplate($page){
    $match=null;
    $tpl=null;
    $file=file_get_contents(SL_PAGES.$page);
    if(preg_match("/{% extends [\"|'|](.+?)[\"|'|] %}/s",$file,$match)){
      $tpl=$match[1];
    }
    return $tpl;
  }

  public static function getPageBlocks($page){
    $matches=null;
    $blocks=array();
    $file=file_get_contents(SL_PAGES.$page);
    if(preg_match_all('/{% block (.*?) %}(.*?){% endblock %}/s',$file,$matches,PREG_SET_ORDER)){
      foreach($matches as $field){
        $blocks[$field[1]]=trim($field[2]);
      }
    }
    return $blocks;
  }

  public static function getTemplateBlocks($template){
    $matches=null;
    $blocks=array();
    $file=file_get_contents(SL_TEMPLATES.$template);
    if(preg_match_all('/{# @editor (.*?) #}\s*{% block (.*?) %}(.*?){% endblock %}/s',$file,$matches,PREG_SET_ORDER)){
      foreach($matches as $field){
        $blocks[$field[2]]=trim($field[1]);
      }
    }
    return $blocks;
  }



}
