<?php
class Link_phone_switchboardController extends CrudController{

    protected function handle_access($action){
        $client_ip = $_SERVER['REMOTE_ADDR'];
        if($client_ip != '62.128.52.101' && $client_ip != '62.90.6.141' && $client_ip != '82.166.145.101'){
            //echo $client_ip."<br/>";
          //  exit("invalid request"); 
        }
        return true;
    }


    //212.143.17.254:80/lfg.php?did=722706388
    public function insert_call(){
        $this->add_model("user_current_phone_calls");
        $call_data = array(
           'call_from' => $_REQUEST['src'],
            'dst' => $_REQUEST['dst'],
            'call_to' => $_REQUEST['dst'],
            'did' => $_REQUEST['did'],
            'link_sy_sidentity' => $_REQUEST['uniqueid'],
        );
        User_current_phone_calls::insert_call($call_data);
    }

}
?>