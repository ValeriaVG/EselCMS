<?php

class sl
{
    private $twig = null;

    public function __construct()
    {
        require_once SL_CORE.'vendor/autoload.php';
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(array(SL_TEMPLATES, SL_TESTS.'tpl'));
        $this->twig = new Twig_Environment($loader, array('cache' => SL_CACHE.'templates'));
    }

    public function _get($attr)
    {
        if (isset($_GET[$attr])) {
            return htmlspecialchars($_GET[$attr]);
        } else {
            return false;
        }
    }

    public function _post($attr)
    {
        if (isset($_POST[$attr])) {
            return htmlspecialchars($_POST[$attr]);
        } else {
            return false;
        }
    }

    public function _request($attr)
    {
        if (isset($_REQUEST[$attr])) {
            return htmlspecialchars($_REQUEST[$attr]);
        } else {
            return false;
        }
    }

    public function _cookie($attr)
    {
        if (isset($_COOKIE[$attr])) {
            return htmlspecialchars($_COOKIE[$attr]);
        } else {
            return false;
        }
    }

    public function _server($attr)
    {
        if (isset($_SERVER[$attr])) {
            return htmlspecialchars($_SERVER[$attr]);
        } else {
            return false;
        }
    }

    public function _session($attr)
    {
        if (isset($_SESSION[$attr])) {
            return htmlspecialchars($_SESSION[$attr]);
        } else {
            return false;
        }
    }

    public function render($filename)
    {
        return $this->twig->render($filename);
    }

    public function route($uri)
    {
        if (empty($uri)) {
            $uri = 'index';
        }
        $template = $uri.'.html';
        if (is_dir(SL_TEMPLATES.$uri)) {
            $template = $uri.'index.html';
        } else {
            $template = $uri.'.html';
        }
        if (file_exists(SL_TEMPLATES.$template)) {
            return $template;
        } else {
            return '404.html';
        }
    }

    public function handleRequest()
    {
        $uri = $this->_get('uri');
        $template = $this->route($uri);

        return $this->render($template);
    }
}
