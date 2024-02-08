<?php
  class Whatsapp_messagesModule extends Module{

    public $add_models = array("whatsapp_conversations", "whatsapp_messages");


    public function list_incoming_message(){
        $message_data = $this->action_data;
        $message_info = json_decode($message_data['message_info'],true);
        print_r_help($message_info);
    }

  }
?>