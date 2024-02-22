<?php
  class CheckController extends CrudController{
    
    protected function check(){
        $this->set_layout("blank");
        $date = new DateTime();
        $now = $date->format('d-m-Y H:i:s');
        $log = $now."\n";

        foreach($_REQUEST as $k=>$v){
            $log.= "$k: $v \n";
        }

        Helper::add_log("check_log.txt","\nHi now is ".$log);
        echo "Hi how are you";
        return;
    }

  }
?>