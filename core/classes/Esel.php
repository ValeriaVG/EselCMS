<?php

class Esel
{
    /**
     * Template Renderer.
     *
     * @var EselRenderer
     */
    public $renderer = null;

    /**
     * Loading vendor classes.
     */
    private function init()
    {
        require_once SL_CORE.'classes/EselRenderer.php';
        $this->renderer = new EselRenderer();
    }
    public function __construct()
    {
        $this->init();
    }
    /**
     * Shorthand for htmlspecialchars.
     *
     * @param int $var Variable to escape or Array
     *
     * @return string $var Sanitized data or Array of it
     */
    public static function clear($var)
    {
        if (is_array($var)) {
            $tmp = array();
            foreach ($var as $key => $value) {
                $tmp[self::clear($key)] = self::clear($value);
            }

            return $tmp;
        }

        return htmlspecialchars($var);
    }
    /**
     * Remove multiple stashes.
     *
     * @param string $path Path to fix
     *
     * @return string $path With no repetative slashes
     */
    public static function fixPath($path)
    {
        return preg_replace('/(\/){2,}/i', '/', $path);
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
            header('Location: '.str_replace('//', '/', ('/'.$uri)));
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
    public function route($uri, $sendHeaders = 1)
    {
        if (empty($uri) || ($uri == '/')) {
            $this->renderer->setData('uri', '/');

            return 'index.html';
        }

        if (preg_match('/^([\/]*)index([\/]*)$/', $uri)) {
            $this->renderer->setData('uri', '/');
            if ($sendHeaders) {
                self::respondWithCode(301, '/');
            }

            return 'index.html';
        }
        if (!preg_match('/\/$/', $uri) || (preg_match('/\/\//', $uri))) {
            $realUri = self::fixPath($uri.'/');
            if ($sendHeaders) {
                self::respondWithCode(301, $realUri);
            }
            $uri = $realUri;
        }
        $this->renderer->setData('uri', self::fixPath('/'.$uri));

        $template = preg_replace("/(\/){1}$/", '', $uri).'.html';

        if (!file_exists(SL_PAGES.$template)) {
            if ($sendHeaders) {
                self::respondWithCode(404);
            }
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
        $uri = '/';
        if (!empty($_GET['uri'])) {
            $uri = $this->clear($_GET['uri']);
        }

        $template = $this->route($uri);
        $output = $this->renderer->render($template);

        return $output;
    }
    /**
     * Initialize and access module object.
     *
     * @param EselModule $moduleName
     *
     * @return moduleName $module
     */
    public function module($moduleName)
    {
        $this->loadModule($moduleName);

        return new $moduleName($this);
    }
    /**
     * Including a module if it passes md5 sum verification.
     *
     * @param string $moduleName
     */
    public static function loadModule($moduleName)
    {
        require_once SL_CORE.'/classes/EselModule.php';
        if (SL_DEV || EselModule::isSafe($moduleName)) {
            require_once SL_MODULES.$moduleName.'/Module.php';
        }
    }

    /**
     * Idiorm wrapper.
     *
     * @param string $table Table name
     *
     * @return ORM cursor
     */
    public static function for_table($table)
    {
        self::connect();

        return ORM::for_table(SL_DB_PREFIX.$table);
    }
    /**
     * Connects to the database.
     *
     * @return Exception if failed
     */
    public static function connect()
    {
        // @codeCoverageIgnoreStart
        if (!class_exists('ORM')) {
            require_once SL_CORE.'lib/idiorm.php';
        }
        // @codeCoverageIgnoreEnd
        ORM::configure(SL_DB_TYPE.':host='.SL_DB_HOST.';dbname='.SL_DB_NAME);
        ORM::configure('username', SL_DB_USER);
        ORM::configure('password', SL_DB_PASS);
    }
    /**
     * Creates specified table with configured prefix.
     *
     * @param string $table   Name of the table to be created without prefix
     * @param array  $columns Array of pairs "column_name"=>"TYPE(SIZE) |NOT| NULL DEFAULT ..."
     *
     * @return Exception if failed
     */
    public static function create_table($table, $columns)
    {
        if (empty($columns)) {
            throw new Exception("Cannot create table '.$table.'- no columns provived");
        }
        $sql = '
        CREATE TABLE IF NOT EXISTS `'.SL_DB_PREFIX.$table.'` (
        `id` int(11) NOT NULL auto_increment, ';
        foreach ($columns as $column => $properties) {
            $sql .= '`'.$column.'` '.$properties.', ';
        }
        $sql .= 'PRIMARY KEY  (`id`));';
        self::connect();
        ORM::raw_execute($sql);
    }
}
