<?php

class EselWebAction extends EselModule
{
    public function handleRequest()
    {

        ob_start();
        $output = null;
        if (preg_match('/actions\/([^\/]+)\/([^\/]+)(\/?)$/', Esel::sga(Esel::GET,'uri'), $tmp)) {
            $module = $tmp[1];
            $action = $tmp[2];
            try {
                $this->Esel->loadModule($module);
                $params=Esel::sga(Esel::POST);
                if(empty($params)){
                  $output=array('success' => true, 'data'=>call_user_func($module."::".$action));
                }else{
                  $output=array('success' => true, 'data'=>call_user_func_array($module."::".$action,$params),'params'=>$params);
                }

            } catch (Exception $e) {
                $output = array('success' => false, 'message' => $e->getMessage());
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
