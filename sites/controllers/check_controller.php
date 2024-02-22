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
        $log.= "\n\nHEADERS: \n";
        foreach (getallheaders() as $name => $value) {
            $log.= "$name: $value\n";
        }

       // Helper::add_log("check_log.txt","\nHi now is ".$log);
        echo nl2br($log);
        return;
    }

  }
?>