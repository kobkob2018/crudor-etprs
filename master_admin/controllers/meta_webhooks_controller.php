<?php
class Meta_webhooksController extends CrudController{

    public function msg_recived(){
        
        $request_smg = "";
        foreach($_REQUEST as $key=>$val){
            $request_smg = "\n$key: $val";
        }
        Helper::add_log('meta_webhooks.txt',"\n\n\n: ".date("m/d/Y H:i", time()).":$request_smg");
        
    }
}
?>