<?php
class Meta_webhooksController extends CrudController{
//this is the controller who recives calls sent directly from link, at real time
    protected function handle_access($action){

        if(isset($_REQUEST['token']) && $_REQUEST['token'] == 'kobkob'){
            Helper::add_log('meta_webhooks_no_token.txt',"\n\n\n: ".date("m/d/Y H:i", time())."-kobkob");
            exit("fail");
        }
        if(isset($_REQUEST['token']) && $_REQUEST['token'] == '1fdb7184e697ab9355a3f1438ddc6ef9'){
            return true;
        }
        
        return false;
    }

    public function ajax_msg_recived(){
        $this->set_layout('blank');
        $request_smg = "";
        foreach($_REQUEST as $key=>$val){
            $request_smg = "\n$key: $val";
        }
        Helper::add_log('meta_webhooks.txt',"\n\n\n: ".date("m/d/Y H:i", time()).":$request_smg");
        exit("ok");
    }
}
?>