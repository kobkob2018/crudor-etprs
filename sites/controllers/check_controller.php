<?php
  class CheckController extends CrudController{
    
    protected function check(){
        $date = new DateTime();
        $now = $date->format('d-m-Y H:i:s');
        Helper::add_log("check_log.txt","\nHi now is ".$now);
        echo "Hi how are you";
        return;
    }

  }
?>