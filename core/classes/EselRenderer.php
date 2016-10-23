<?php
class EselRenderer{

  private $twig;
  /**
   * Data that goes to template.
   *
   * @var array
   */
  private $data = array();

  public function __construct(){
    require_once SL_CORE.'vendor/autoload.php';
    Twig_Autoloader::register();
    $loader = new Twig_Loader_Filesystem(array(SL_TEMPLATES, SL_PAGES, SL_TESTS.'tpl'));
    $this->twig = new Twig_Environment($loader, array('cache' => SL_TEMPLATES_CACHE, 'debug' => true));
    $this->twig->addExtension(new Twig_Extension_Debug());
    $this->twig->registerUndefinedFunctionCallback(function ($functionName) {
        $tmp = null;
        if (preg_match('/([^_]+)_(.*)/', $functionName, $tmp)) {
            Esel::loadModule($tmp[1]);
            return new Twig_SimpleFunction($functionName, $tmp[1].'::'.$tmp[2]);
        }

        return false;
    });
    return $this;

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
   * Sets element of the array that template gets.
   *
   * @param mixed $key
   * @param mixed $value
   */
  public function setData($key, $value)
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
          return $this->data;
      }

      return $this->data[$key];
  }
}
