<?php

class sl
{
    /**
     * Twig Enviroment variable.
     *
     * @var Twig_Environment
     */
    private $twig = null;
    /**
     * Data that goes to template.
     *
     * @var array
     */
    private $data = array();

/**
 * Setting constants for global array names
 * GET,POST,REQUEST,COOKIE,SESSION,SERVER.
 */
const GET = 0;
    const POST = 1;
    const REQUEST = 2;
    const COOKIE = 3;
    const SESSION = 4;
    const SERVER = 5;

    /**
     * Loading vendor classes.
     */
    private function init()
    {
        require_once SL_CORE.'vendor/autoload.php';
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(array(SL_TEMPLATES, SL_PAGES, SL_TESTS.'tpl'));
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
    public function __construct()
    {
        $this->init();
    }
    /**
     * Global arrays sanitizing.
     *
     * @param int    $var  GET,POST,REQUEST,COOKIE,SESSION,SERVER
     * @param string $attr Variable name
     *
     * @return string $var Sanitized data or array of sanitized data
     */
    public static function g($var, $attr = '')
    {
        switch ($var) {
          case self::GET:
            if (empty($attr)) {
                $var = array();
                foreach ($_GET as $key => $value) {
                    $var[$key] = self::g(self::GET, $key);
                }

                return $var;
            }
            $var = '';
            if (isset($_GET[$attr])) {
                $var = htmlspecialchars($_GET[$attr]);
            }

            return $var;
          break;

          case self::POST:
            if (empty($attr)) {
                $var = array();
                foreach ($_POST as $key => $value) {
                    $var[$key] = self::g(self::POST, $key);
                }

                return $var;
            }
            $var = '';
            if (isset($_POST[$attr])) {
                $var = htmlspecialchars($_POST[$attr]);
            }

            return $var;
          break;

          case self::REQUEST:
            if (empty($attr)) {
                $var = array();
                foreach ($_REQUEST as $key => $value) {
                    $var[$key] = self::g(self::REQUEST, $key);
                }

                return $var;
            }
            $var = '';
            if (isset($_REQUEST[$attr])) {
                $var = htmlspecialchars($_REQUEST[$attr]);
            }

            return $var;
          break;

          case self::COOKIE:
            if (empty($attr)) {
                $var = array();
                foreach ($_COOKIE as $key => $value) {
                    $var[$key] = self::g(self::COOKIE, $key);
                }

                return $var;
            }
            $var = '';
            if (isset($_COOKIE[$attr])) {
                $var = htmlspecialchars($_COOKIE[$attr]);
            }

            return $var;
          break;

          case self::SESSION:
            if (empty($attr)) {
                $var = array();
                foreach ($_SESSION as $key => $value) {
                    $var[$key] = self::g(self::SESSION, $key);
                }

                return $var;
            }
            $var = '';
            if (isset($_SESSION[$attr])) {
                $var = htmlspecialchars($_SESSION[$attr]);
            }

            return $var;
          break;

          case self::SERVER:
            $var = '';
            if (isset($_SERVER[$attr])) {
                $var = htmlspecialchars($_SERVER[$attr]);
            }

            return $var;
          break;

          default:
           return null;

      }
    }

    /**
     * Renders given template file.
     *
     * @param string $filename
     *
     * @return string $output
     */
    public function render($filename)
    {
        $output = $this->twig->render($filename, $this->data);

        return $output;
    }
    /**
     * Sets up corresponding header and redirects to sprecified url.
     *
     * @param int    $code
     * @param strong $uri
     */
    public static function respondWithCode($code, $uri = null)
    {
        switch ($code) {
        case 301:
          header('HTTP/1.1 301 Moved Permanently');
        break;
        case 404:
          header('HTTP/1.0 404 Not Found');
        break;
      }

        if (!empty($uri)) {
            header('Location: '.$uri);
            if (!@PHPUNIT_RUNNING === 1) {
            // @codeCoverageIgnoreStart
              exit();
            // @codeCoverageIgnoreEnd
            }
        }
    }
    /**
     * Basic SEO routing following page files structure.
     *
     * @param string $uri Requested uri
     *
     * @return string $template Template file
     */
    public function route($uri)
    {
        if (preg_match("/index(\/?)/", $uri)) {
            $rootUri = preg_replace('/(\/){2,}/i', '/', (preg_replace("/index(\/?)/", '', $uri).'/'));
            self::respondWithCode(301, $rootUri);
            $uri = $rootUri;
        }
        if (!preg_match('/\/$/', $uri) || (preg_match('/\/\//', $uri))) {
            $realUri = preg_replace('/(\/){2,}/i', '/', ($uri.'/'));
            self::respondWithCode(301, $realUri);
            $uri = $realUri;
        }
        $this->data['uri'] = $uri;
        if (empty($uri) || ($uri == '/')) {
            $uri = 'index';
        }

        if (is_dir(SL_PAGES.$uri)) {
            $template = $uri.'index.html';
        } else {
            $template = preg_replace("/(\/){1}$/", '', $uri).'.html';
        }
        if (!file_exists(SL_PAGES.$template)) {
            self::respondWithCode(404);
            $template = '404.html';
        }

        return $template;
    }
    /**
     * Request processor.
     *
     * @return string $output
     */
    public function handleRequest()
    {
        $uri = $this->g(self::GET,'uri');
        $template = $this->route($uri);
        $output = $this->render($template);

        return $output;
    }
    /**
     * Initialize and access module object.
     *
     * @param slModule $moduleName
     *
     * @return moduleName $module
     */
    public function module($moduleName)
    {
        $this->loadModule($moduleName);
        $module = new $moduleName($this);

        return $module;
    }
    /**
     * Including a module if it passes md5 sum verification.
     *
     * @param string $moduleName
     */
    public function loadModule($moduleName)
    {
        require_once SL_CORE.'/classes/slModule.php';
        if (slModule::isSafe($moduleName)) {
            require_once SL_MODULES.$moduleName.'/module.php';
        }
    }
    /**
     * Adds data to the array that template gets.
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function addData($key, $value)
    {
        $this->data[$key] = $value;
    }
    /**
     * Retuns array of data or it's element if key specified.
     *
     * @param mixed $key
     *
     * @return mixed $data
     */
    public function getData($key = null)
    {
        if ($key === null) {
            $data = $this->data;
        } else {
            $data = $this->data[$key];
        }

        return $data;
    }
    /**
     * Idiorm wrapper.
     *
     * @param string $table Table name
     *
     * @return ORM cursor
     */
    public static function db($table)
    {
        if (!class_exists('ORM')) {
            require_once SL_CORE.'lib/idiorm.php';
        }
        ORM::configure(SL_DB_TYPE.':host='.SL_DB_HOST.';dbname='.SL_DB_NAME);
        ORM::configure('username', SL_DB_USER);
        ORM::configure('password', SL_DB_PASS);

        return ORM::for_table(SL_DB_PREFIX.$table);
    }
}
