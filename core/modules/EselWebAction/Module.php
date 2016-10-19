<?php

class EselWebAction extends EselModule
{
    public function handleRequest()
    {

        ob_start();
        $output = null;
        if (preg_match('/actions\/([^\/]+)\/([^\/]+)(\/?)$/', Esel::clear($_GET['uri']), $tmp)) {
            $module = $tmp[1];
            $action = $tmp[2];
            $params=Esel::clear($_POST);
            try {
                $this->Esel->loadModule($module);
                $parameters=array();
                $r = new ReflectionMethod($module, $action);
                $args = $r->getParameters();

                foreach ($args as $arg) {

                  $parameters[$arg->getName()]=null;
                  if(isset($params[$arg->getName()])){
                    $parameters[$arg->getName()]=$params[$arg->getName()];
                  }

                }
                if(empty($parameters)){
                  $output=array('success' => true, 'data'=>call_user_func($module."::".$action));
                }else{

                  $output=array('success' => true, 'data'=>call_user_func_array($module."::".$action,$parameters),'params'=>$parameters);
                }

            } catch (Exception $e) {
                $output = array('success' => false, 'message' => $e->getMessage(),'errors'=>error_get_last(),'params'=>$params);
            }
        } else {
            $output = array('success' => false, 'message' => 'No action specified');
        }
        ob_end_clean();

        if (@PHPUNIT_RUNNING === 1) {

          return json_encode($output, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        }
        // @codeCoverageIgnoreStart
        header('Content-Type: application/json');
        echo json_encode($output, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        exit();
        // @codeCoverageIgnoreEnd
    }
}
