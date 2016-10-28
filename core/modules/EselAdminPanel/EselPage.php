<?php
class EselPage{
  public $name;
  public $folder;
  public $url;
  public $new=false;

  public static function makeUrl($relativePath){
    if(!file_exists(SL_PAGES.$relativePath)){
      throw new Exception(EselModule::getLexicon("EselAdminPanel","page_doesnt_exist",array("relativePath"=>SL_PAGES.$relativePath)));

    }
    if(preg_match("/^(\/|)index\.html$/",$relativePath)){
      return "/";
    }
    $url=preg_replace("/(\.html)/","",$relativePath);
    return Esel::fixPath("/".$url."/");
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
      foreach($matches as $match){
        $field=new stdClass();
        $field->name=trim($match[2]);
        $field->type=trim($match[1]);
        $field->label="";
        $field->hint="";
        $tmp=array();
        if(preg_match('/{# @label (.*?) #}/s',$match[3],$tmp)){
        $field->label=$tmp[1];
        }
        $tmp=array();
        if(preg_match('/{# @hint (.*?) #}/s',$match[3],$tmp)){
        $field->hint=$tmp[1];
        }
        $blocks[trim($match[2])]=$field;
      }
    }
    return $blocks;
  }



}
