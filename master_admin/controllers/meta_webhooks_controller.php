<?php
class Meta_webhooksController extends CrudController{

    public function clear_log(){
        Helper::clear_log('meta_webhooks.txt');
        Helper::clear_log('meta_webhooks_log.txt');
        exit("ok");
    }

    public function msg_recived(){
        $this->set_layout('blank');
        
        $request_body = file_get_contents('php://input');
        $this->call_module('whatsapp_messages','handle_message_notification',array('message_info'=>$request_body));
    }
}
?>