<?php
  class Whatsapp_messagesModule extends Module{

    public $add_models = array("whatsapp_settings", "whatsapp_conversations", "whatsapp_messages");


    public function list_incoming_message(){
        $message_data = $this->action_data;
        $message_info = json_decode($message_data['message_info'],true);

        print_r_help($message_info);
    }

    public function list_outcoming_message(){
        $message_data = $this->action_data;
        $message_info = json_decode($message_data['message_info'],true);
        
        print_r_help($message_info);
    }

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
        );
        $message_id = Whatsapp_messages::create($message_row_data);
        $conversation_update = array(
            'last_message_id'=>$message_id,
            'last_message_time'=>date('Y-m-d h:i:s'),
        );
        Whatsapp_conversations::update($conversation_id,$conversation_update);
        return $this->controller->redirect_to(inner_url("whatsapp_messages/add/?conversation_id=".$conversation_id));
    }

    protected function send_message_with_api($conversation_data,$message_data){
        $owner_phone_id = $conversation_data['owner_phone_id'];
        $to = $conversation_data['contact_phone_wa_id'];
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
        return $result_arr;
    }

	protected function create_log($log_arr,$log_str = "",$deep=-1){
		$deep++;
		foreach($log_arr as $key=>$val){
			
			$log_str .="\n$key: ";
			if(!is_array($val)){
                for($gap=0;$gap<$deep+1;$gap++){
                    $log_str .="- ";    
                }
				$log_str .="  $val";
			}
			else{
                for($gap=0;$gap<$deep;$gap++){
                    $log_str .="- ";    
                }
				$log_str = $this->create_log($val,$log_str,$deep);
			}
		}
		return $log_str;
	}

    public function handle_message_notification(){

        $message_data = $this->action_data;
        $message_info = json_decode($message_data['message_info'],true);
       Helper::add_log('meta_webhooks_log.txt',$message_data['message_info']);
	   $log = $this->create_log($message_info);
        Helper::add_log('meta_webhooks.txt',$log);
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
        Helper::add_log('meta_webhooks.txt',"\n\n\n OK A OK");
        $metadata = $message_info['entry'][0]['changes'][0]['value']['metadata'];
        $contact = $message_info['entry'][0]['changes'][0]['value']['contacts'][0];
        $message = $message_info['entry'][0]['changes'][0]['value']['messages'][0];
        $self_phone = $metadata['display_phone_number'];
        $contact_phone = $contact['wa_id'];
        $connection_id = $self_phone."_".$contact_phone;
        Helper::add_log('meta_webhooks.txt',"\n\n\n OK B OK");
        $filter_arr = array("connection_id"=>$connection_id,"stage"=>"open");
        $conversation_row = Whatsapp_conversations::find($filter_arr,'id');
        $conversation_id = false;
        Helper::add_log('meta_webhooks.txt',"\n OK C OK-".$connection_id);
        if(!$conversation_row){
            Helper::add_log('meta_webhooks.txt',"\n NOT C found");
            $conversation_id = $this->add_conversation($metadata,$contact,$message,$connection_id);
        }
        else{
            Helper::add_log('meta_webhooks.txt',"\nYes C found");
            $conversation_id = $conversation_row['id'];
        }
        Helper::add_log('meta_webhooks.txt',"\n STEAL HERE");

        $message_type = "text";
        $message_text = "";
        $message_time = "";
        if(isset($message['text'])){
            $message_text = $message['text']['body'];
            $message_time = $message['timestamp'];
        }
        if(isset($message['context'])){
            Helper::add_log('meta_webhooks.txt',"\nTHIS IS A CONTEXT MESSAGE");
        }
        if(isset($message['button'])){
            Helper::add_log('meta_webhooks.txt',"\nTHIS IS A BUTTON MESSAGE");
            $message_text = $message['context']['button']['text'];   
        }
        $message_row_data = array(
            'conversation_id'=>$conversation_id,
            'connection_id'=>$connection_id,
            'message_time'=>date('Y-m-d h:i:s',$message_time),
            'message_type'=>$message_type,
            'message_text'=>$message_text,
            'direction'=>'recive',
            'log'=>$message_data['message_info']
        );
        Helper::add_log('meta_webhooks.txt',"\n\n\n YET AGAIN");
        $message_id = Whatsapp_messages::create($message_row_data);
        Helper::add_log('meta_webhooks.txt',"\n\n\n MESSAGE CREATED");
        $conversation_update = array(
            'last_message_id'=>$message_id,
            'last_message_time'=>date('Y-m-d h:i:s',$message_time),
        );
        Whatsapp_conversations::update($conversation_id,$conversation_update);
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