<?php
  class CheckController extends CrudController{
    
    protected function check(){

        $api_key = get_config("curl_key");
        $headers = getallheaders();
        if($headers['authorization'] != "Bearer $api_key"){
            exit('"'.$headers['authorization'].'"');
            exit("permission denied - code 203");
        }

        if($_SERVER['REMOTE_ADDR'] != get_config('server_ip')){
            exit("permission denied - code 203");
        }       


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


        $request_body = file_get_contents('php://input');

        $log.=$request_body;

       // Helper::add_log("check_log.txt","\nHi now is ".$log);
        echo nl2br($log);
        return;
    }

  }
?>