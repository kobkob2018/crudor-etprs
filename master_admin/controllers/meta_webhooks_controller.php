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

    protected function register_notification_as_is($message_info_json){
        $log_txt = "\n\n-------------------------\n";
        foreach($_REQUEST as $key=>$val){
            $log_txt .= $key.":";
            if(!is_array($val)){
                $log_txt.= " ".$val; 
            }
            else{
                $log_txt.=" array";
            }
            //$log_txt.="\n".$message_info_json."\n";
            $log_txt.="-------------------------------\n";

        }
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