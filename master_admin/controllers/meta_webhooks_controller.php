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
        $this->register_notification_as_is($request_body);

        $this->call_module('whatsapp_messages','handle_message_notification',array('message_info'=>$request_body));
    }

    public function check_long_json(){
        $long_json = '{"object":"whatsapp_business_account","entry":[{"id":"288442484341750","changes":[{"value":{"messaging_product":"whatsapp","metadata":{"display_phone_number":"972722706389","phone_number_id":"246712598531783"},"contacts":[{"profile":{"name":"yacov avr"},"wa_id":"972542393397"}],"messages":[{"from":"972542393397","id":"wamid.HBgMOTcyNTQyMzkzMzk3FQIAEhggQTZBOTRCQjIzMkYxOTlFNjJCOTY3NDNBOUVDQTI5MzAA","timestamp":"1712655931","text":{"body":"\u05d5\u05d6\u05d4 \u05d5\u05d6\u05d4 \u05df\u05d6\u05d4"},"type":"text"}]},"field":"messages"}]}]}';
        $this->call_module('whatsapp_messages','handle_message_notification',array('message_info'=>$long_json));

    }

    protected function register_notification_as_is($message_info_json){
        $log_txt = "\n-------------------------\n";
        foreach($_REQUEST as $key=>$val){
            $log_txt .= $key.":";
            if(!is_array($val)){
                $log_txt.= " ".$val; 
            }
            else{
                $log_txt.=" array";
            }
            
            

        }
        $log_txt.="\n".$message_info_json."\n";
        $log_txt.="-------------------------------\n";
        Helper::add_log('webhooks_notes.txt',$log_txt);
        $message_arr = array('info'=>$message_info_json);
        TableModel::simple_create_by_table_name($message_arr,'whatsapp_notifications');
    }

    public function clear_notifications_log(){
        Helper::clear_log('webhooks_notes.txt');
        SystemMessages::add_success_message("log is cleared");
        $this->redirect_to(inner_url('whatsapp_notifications/list/'));
    }
}
?>