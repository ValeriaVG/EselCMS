<?php

class WebAction extends EselModule
{
    public function handleRequest()
    {
        header('Content-Type: application/json');
        ob_start();
        $output = null;
        if (preg_match('/actions\/([^\/]+)\/([^\/]+)(\/?)$/', Esel::g(Esel::GET,'uri'), $tmp)) {
            $module = $tmp[1];
            $action = $tmp[2];
            try {
                Esel::loadModule($module);
                $params=Esel::_post();
                if(empty($params)){
                  $output=array('success' => true, 'data'=>call_user_func($module."::".$action));
                }else{
                  $output=array('success' => true, 'data'=>call_user_func_array($module."::".$action,$params));
                }

            } catch (Exception $e) {
                $output = array('success' => false, 'message' => $e->getMessage());
            }
        } else {
            $output = array('success' => false, 'message' => 'No action specified');
        }
        ob_end_clean();
        echo json_encode($output);
        exit();
    }
}
