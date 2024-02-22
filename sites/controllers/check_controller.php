<?php
  class CheckController extends CrudController{
    
    protected function check(){
        $this->set_layout("blank");
        $api_key = get_config("curl_key");
        $headers = getallheaders();
        if($headers['authorization'] != "Bearer $api_key"){
            exit('"'.$headers['authorization'].'"');
            exit("permission denied - code 203");
        }

        if($_SERVER['REMOTE_ADDR'] != get_config('server_ip')){
            exit("permission denied - code 203");
        }       


        

        $request_body = file_get_contents('php://input');

        $request_arr = json_decode($request_body,true);
        print_r_help(($request_arr));
       // Helper::add_log("check_log.txt","\nHi now is ".$log);
        //echo nl2br($log);
        return;
    }

  }
?>