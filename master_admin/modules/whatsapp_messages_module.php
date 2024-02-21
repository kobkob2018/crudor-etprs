<?php
  class Whatsapp_messagesModule extends Module{

    public $add_models = array("whatsapp_settings", "whatsapp_conversations", "whatsapp_messages");

    public function send_message(){
        $message_data = $this->action_data;
        $conversation_id = $message_data['conversation_id'];
        $conversation_data = Whatsapp_conversations::get_by_id($conversation_id);
        if(!$conversation_data){
            exit("error finding conversation");
        }
        $message_send = $this->send_message_with_api($conversation_data,$message_data);
        if(isset($message_send['error'])){
            SystemMessages::add_err_message($message_send['error']['message']);
            if(isset($message_send['error']['error_data'])){
                SystemMessages::add_err_message($message_send['error']['error_data']['details']);
            }
            return $this->controller->redirect_to(inner_url("whatsapp_messages/add/?conversation_id=".$conversation_id));
        }
        $connection_id = $conversation_data['connection_id'];
        $message_row_data = array(
            'conversation_id'=>$conversation_id,
            'connection_id'=>$connection_id,
            'message_time'=>date('Y-m-d h:i:s'),
            'message_text'=>$message_data['message_text'],
            'message_type'=>$message_data['message_type'],
            'direction'=>'send',
            'log'=>$message_send['log'],
            'wamid'=>$message_send['messages'][0]['id']
        );
        $message_id = Whatsapp_messages::create($message_row_data);
        $conversation_update = array(
            'last_message_id'=>$message_id,
            'last_message_time'=>date('Y-m-d h:i:s'),
        );
        Whatsapp_conversations::update($conversation_id,$conversation_update);
        return $this->controller->redirect_to(inner_url("whatsapp_messages/add/?conversation_id=".$conversation_id));
    }

    protected function send_message_with_api($conversation_data,$message_data, $to = ""){
        $owner_phone_id = $conversation_data['owner_phone_id'];
        if($to ==""){
            $to = $conversation_data['contact_phone_wa_id'];
        }
        $api_key = Whatsapp_settings::get()['messages_api_key'];
        $url = "https://graph.facebook.com/v17.0/$owner_phone_id/messages";

        // Create a new cURL resource
        $ch = curl_init($url);

        $data = array(
            'messaging_product'=> "whatsapp",
            "to"=> $to,
            "type"=> $message_data['message_type'],
        );
        if($message_data['message_type'] == 'template'){
            $data['template'] = array(
                "name"=> $message_data['message_text'],
                "language"=> array(
                    "code"=> $message_data['template_language']
                )
            );
        }
        if($message_data['message_type'] == 'text'){
            $data['text'] = array(
                "preview_url"=> false,
                "body" => $message_data['message_text'],
            );
        }
        
        $payload = json_encode($data);
        Helper::add_log('meta_webhooks_admin.txt',"\n\n\n$to: $payload");
        Helper::add_log('meta_webhooks_admin.txt',"\n\n\n$api_key");
        Helper::add_log('meta_webhooks_admin.txt',"\n\n\n$url");
        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer '.$api_key));

        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the POST request
        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);
        $result_arr = json_decode($result,true);
        $result_arr['log'] = $result;
        return $result_arr;
    }

    public function handle_message_notification(){

        $message_data = $this->action_data;
        $message_info = json_decode($message_data['message_info'],true);

        if(
            (!isset($message_info['entry'][0])) ||
            (!isset($message_info['entry'][0]['changes'][0])) ||
            (!isset($message_info['entry'][0]['changes'][0]['value'])) ||
            (!isset($message_info['entry'][0]['changes'][0]['value']['contacts'][0])) ||
            (!isset($message_info['entry'][0]['changes'][0]['value']['metadata'])) ||
            (!isset($message_info['entry'][0]['changes'][0]['value']['messages'][0]))
        ){
            
            return false;
        }
        
        $metadata = $message_info['entry'][0]['changes'][0]['value']['metadata'];
        $contact = $message_info['entry'][0]['changes'][0]['value']['contacts'][0];
        $message = $message_info['entry'][0]['changes'][0]['value']['messages'][0];
        $self_phone = $metadata['display_phone_number'];
        $contact_phone = $contact['wa_id'];
        $connection_id = $self_phone."_".$contact_phone;
        
        $filter_arr = array("connection_id"=>$connection_id,"stage"=>"open");
        $conversation_row = Whatsapp_conversations::find($filter_arr);
        $conversation_id = false;
        
        if(!$conversation_row){
            $conversation_id = $this->add_conversation($metadata,$contact,$message,$connection_id);
            $conversation_row = Whatsapp_conversations::get_by_id($conversation_id);
        }
        else{
            $conversation_id = $conversation_row['id'];
        }
        

        $message_type = "text";
        $message_text = "";
        $message_time = "";
        
        if(isset($message['timestamp'])){
            $message_time = $message['timestamp'];
        }
        if(isset($message['text'])){
            $message_text = $message['text']['body'];
        }
        $admin_alerts_phone = Whatsapp_settings::get()['admin_alerts_phone'];
        $wamid_message = false;
        $sender_is_admin = false;
        $message_is_reply_from_admin = false;
        if($contact_phone == $admin_alerts_phone){
            $sender_is_admin = true;
        }
        $direction = 'recive';
        if(isset($message['context'])){
            $wamid_filter = array('wamid'=>$message['context']['id']);
            if($sender_is_admin){
                $wamid_filter = array('admin_wamid'=>$message['context']['id']);
            }

            $wamid_message = Whatsapp_messages::find($wamid_filter,'id');
            
            if($wamid_message){
                if($sender_is_admin){
                    $message_is_reply_from_admin = true;
                    $conversation_id = $wamid_message['conversation_id'];
                    $connection_id = $wamid_message['connection_id'];
                    $direction = 'send';
                }    
                
                
                
            }
        }
        if(isset($message['button'])){
            $message_text = $message['button']['text'];   
        }
        $message_row_data = array(
            'conversation_id'=>$conversation_id,
            'connection_id'=>$connection_id,
            'message_time'=>date('Y-m-d h:i:s',$message_time),
            'message_type'=>$message_type,
            'message_text'=>$message_text,
            'direction'=>$direction,
            'log'=>$message_data['message_info'],
            'wamid'=>$message['id']
        );
        
        if($direction=='recive'){
            
            if($wamid_message){
                $message_row_data['context'] = $wamid_message['id'];
            }
        }
        $message_id = Whatsapp_messages::create($message_row_data);
        $message_row_data['id'] = $message_id;
        if($direction=='send'){
            $this->foreword_message_from_admin($conversation_row,$message_row_data);
        }
        
        
        if($direction=='recive'){
            $this->send_alert_to_admin($conversation_row,$message_row_data);

        }
        
        $conversation_update = array(
            'last_message_id'=>$message_id,
            'last_message_time'=>date('Y-m-d h:i:s',$message_time),
        );
        Whatsapp_conversations::update($conversation_id,$conversation_update);
    }

    protected function foreword_message_from_admin($conversation_row,$message_row_data){
        return $this->send_message_with_api($conversation_row,$message_row_data);
    }

    protected function send_alert_to_admin($conversation_data, $message_row_data){
        Helper::add_log('meta_webhooks_admin.txt',"\n\n\n MESSAGE CREATing");
        $to = Whatsapp_settings::get()['admin_alerts_phone'];
        
        $message_text = "message from system: \n\n";
        $message_text .= "connection_id: ".$message_row_data['connection_id']."\n";
        $message_text .= "message: \n";
        $message_text .= $message_row_data['message_type'].": \n".$message_row_data['message_text'];
        $message_text .= "\n\nview in admin: ".outer_url('whatsapp_messages/add/?conversation_id='.$conversation_data['id']);
        Helper::add_log('meta_webhooks_admin.txt',"\n\n\n $message_text");

        $message_data = array(
            'message_type'=>'text',
            'message_text'=>$message_text,
        );
        $message_send = $this->send_message_with_api($conversation_data,$message_data,$to);
        $admin_wamid = $message_send['messages'][0]['id'];
        Whatsapp_messages::update($message_row_data['id'],array('admin_wamid'=>$admin_wamid));
    }

    protected function add_conversation($metadata,$contact,$message,$connection_id){
        $conversation_data = array(
            'connection_id'=>$connection_id,
            'owner_phone'=>$metadata['display_phone_number'],
            'owner_phone_id'=>$metadata['phone_number_id'],
            'contact_phone_wa_id'=>$contact['wa_id'],
            'contact_wa_name'=>$contact['profile']['name'],
            'contact_custom_name'=>"",
            'last_message_time'=>date('Y-m-d h:i:s',$message['timestamp']),
            'last_message_direction'=>'recive',
            'stage'=>'open'
        );
        $row_id = Whatsapp_conversations::create($conversation_data);
        return $row_id;
    }

  }
?>