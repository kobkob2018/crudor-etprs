<?php
class Meta_webhooksController extends CrudController{

    public function clear_log(){
        Helper::clear_log('meta_webhooks.txt');
        exit("ok");
    }

    public function msg_recived(){
        $this->set_layout('blank');
        
        $request_smg = "";
        foreach($_REQUEST as $key=>$val){
            $request_smg .= "\n$key: $val";
        }
        $request_body = file_get_contents('php://input');
        $request_smg .= "\n".$request_body;
        Helper::add_log('meta_webhooks.txt',"\n\n\n: ".date("m/d/Y H:i", time()).":$request_smg");
        $exit_str = "";
        if(isset($_REQUEST['hub_challenge'])){
            $exit_str = $_REQUEST['hub_challenge'];
        }
        exit($exit_str);
    }
}
?>