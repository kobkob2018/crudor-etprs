<?php
  class Pixel_postController extends CrudController{
    
    public function transfer() {

        $this->set_layout('blank');
        $url_to = $_REQUEST['url_to'];
       // print_r_help($_REQUEST);
        $params_arr = json_decode($_REQUEST['params'],true);
        $params = "";
        $params_qstr_arr = array();
        foreach($params_arr as $key=>$value){
            $params_qstr_arr[] = "$key=$value";
        }
        $params.= implode("&",$params_qstr_arr);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url_to);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
    }
  }


?>