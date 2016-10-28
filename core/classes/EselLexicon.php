<?php

class EselLexicon
{

    public static function get($var, $params = array(), $language = "", $module = "", $topic = "default")
    {
        if(empty($language)){
          $language=SL_LANGUAGE;
        }
        $path = SL_MODULES;
        if (empty($module)) {
            $path = SL_CORE;
        }
        $path .= $module."/lexicon/".$language."/".$topic.".inc.php";
        $path = Esel::fixPath($path);
        if (!file_exists($path)) {
            return $var;
        }
        include $path;
        if (isset($lang[$var])) {
            require_once SL_CORE."classes/EselRenderer.php";
            $renderer=new EselRenderer();
            return $renderer->renderString($lang[$var], $params);
        }

        return $var;
    }
}
