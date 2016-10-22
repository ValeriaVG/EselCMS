<?php

class EselEvent extends EselModule
{
    // @codeCoverageIgnoreStart
    public function install()
    {
        $this->Esel->create_table('events', array('event' => 'VARCHAR(255) NOT NULL', 'method' => 'VARCHAR(255) NOT NULL','order'=>'INT(10) NOT NULL DEFAULT 0'));
    }
    // @codeCoverageIgnoreEnd
    public static function addListener($eventName, $methodToCall, $order = 0)
    {
        $event=Esel::for_table('events')->where('event',$eventName)->where('method',$methodToCall)->find_one();
        if(!$event){
        $event = Esel::for_table('events')->create();
        $event->set('event', $eventName);
        $event->set('method', $methodToCall);
        }
        $event->set('order', $order);
        $event->save();
    }

    public static function invoke($eventName,$parameters=array()){
      $events=Esel::for_table('events')->where('event',$eventName)->find_many();
      $res=true;
      foreach($events as $event){
        if(empty($parameters)){
            // @codeCoverageIgnoreStart
          $res=call_user_func($event->get("method"));
            // @codeCoverageIgnoreEnd
        }else{
          $res=call_user_func_array($event->get("method"),$parameters);
        }
      }
      return $res;
    }
}
