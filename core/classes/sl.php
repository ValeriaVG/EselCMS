<?php

class sl
{
    private $twig = null;

    public function __construct()
    {
        require_once SL_CORE.'vendor/autoload.php';
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(array(SL_TEMPLATES, SL_TESTS.'tpl'));
        $this->twig = new Twig_Environment($loader, array('cache' => SL_TEMPLATES_CACHE));
        $this->twig->registerUndefinedFunctionCallback(function ($functionName) {
            $tmp = null;
            if (preg_match('/([^_]+)_(.*)/', $functionName, $tmp)) {
                $module = $this->loadModule($tmp[1]);

                return new Twig_SimpleFunction($functionName, $tmp[1].'::'.$tmp[2]);
            }

            return false;
        });
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
        if (preg_match("/index(\/?)/", $uri)) {
            header('HTTP/1.1 301 Moved Permanently');
            $rootUri = preg_replace('/(\/){2,}/i', '/', (preg_replace("/index(\/?)/", '', $uri).'/'));
            header('Location: '.$rootUri);
            if (!@PHPUNIT_RUNNING === 1) {
                // @codeCoverageIgnoreStart
                exit();
                // @codeCoverageIgnoreEnd
            } else {
                return '301 to: '.$rootUri;
            }
        }
        if (!preg_match('/\/$/', $uri) || (preg_match('/\/\//', $uri))) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '.preg_replace('/(\/){2,}/i', '/', ($uri.'/')));
                // @codeCoverageIgnoreStart
              exit();
              // @codeCoverageIgnoreEnd
        }

        if (empty($uri) || ($uri == '/')) {
            $uri = 'index';
        }

        if (is_dir(SL_TEMPLATES.$uri)) {
            $template = $uri.'index.html';
        } else {
            $template = preg_replace("/(\/){1}$/", '', $uri).'.html';
        }
        if (file_exists(SL_TEMPLATES.$template)) {
            return $template;
        } else {
            header('HTTP/1.0 404 Not Found');

            return '404.html';
        }
    }

    public function handleRequest()
    {
        $uri = $this->_get('uri');
        $template = $this->route($uri);

        return $this->render($template);
    }

    public function module($moduleName)
    {
        $this->loadModule($moduleName);
        return new $moduleName($this);
    }

    public function loadModule($moduleName)
    {
        require_once SL_CORE.'/classes/slmodule.php';
        if (slModule::isSafe($moduleName)) {
            require_once SL_MODULES.$moduleName.'/module.php';
        } else {
            throw new Exception($moduleName.' is not installed');
        }
    }
}
