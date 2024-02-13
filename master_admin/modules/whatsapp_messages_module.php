<?php
  class Whatsapp_messagesModule extends Module{

    public $add_models = array("whatsapp_conversations", "whatsapp_messages");


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
        $this->send_message_with_api($conversation_data,$message_data);
        $connection_id = $conversation_data['connection_id'];
        $message_row_data = array(
            'conversation_id'=>$connection_id,
            'connection_id'=>$connection_id,
            'message_time'=>date('Y-m-d h:i:s'),
            'message_text'=>$message_data['text'],
            'message_type'=>$message_data['type'],
            'message_template'=>$message_data['template'],
            'direction'=>'send',
        );
        $message_id = Whatsapp_messages::create($message_row_data);
        $conversation_update = array(
            'last_message_id'=>$message_id,
            'last_message_time'=>date('Y-m-d h:i:s'),
        );
        Whatsapp_conversations::update($conversation_id,$conversation_update);
        
    }

    protected function send_message_with_api($conversation_data,$message_data){
        $owner_phone_id = $conversation_data['owner_phone_id'];
        $to = $conversation_data['contact_phone_wa_id'];
        $api_key = "";// add api key here...
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
                    "code"=> "he"
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
    }

    public function handle_message_notification(){
        $message_data = $this->action_data;
        $message_info = json_decode($message_data['message_info'],true);
        if(
            (!isset($message_info['entry'])) ||
            (!isset($message_info['entry']['changes'])) ||
            (!isset($message_info['entry']['changes']['value'])) ||
            (!isset($message_info['entry']['changes']['value']['contacts'])) ||
            (!isset($message_info['entry']['changes']['value']['metadata'])) ||
            (!isset($message_info['entry']['changes']['value']['messages']))
        ){
            return false;
        }
        $metadata = $message_info['entry']['changes']['value']['contacts'];
        $contact = $message_info['entry']['changes']['value']['contacts'];
        $message = $message_info['entry']['changes']['value']['messages'];
        $self_phone = $metadata['display_phone_number'];
        $contact_phone = $metadata['display_phone_number'];
        $connection_id = $self_phone."_".$contact_phone;
        $filter_arr = array("connection_id"=>$connection_id,"stage"=>"open");
        $conversation_row = Whatsapp_conversations::find($filter_arr,'id');
        $conversation_id = false;
        if(!$conversation_row){
            $conversation_id = $this->add_conversation($metadata,$contact,$message,$connection_id);
        }
        else{
            $conversation_id = $conversation_row['id'];
        }
        $message_data = array(
            'convertion_id'=>$conversation_id,
            'connection_id'=>$connection_id,
            'message_time'=>date('Y-m-d h:i:s',$message['timestamp']),
            'message_type'=>'text',
            'message_text'=>$message['text']['body'],
            'direction'=>'recive',
        );
        $message_id = Whatsapp_messages::create($message_data);
        $conversation_update = array(
            'last_message_id'=>$message_id,
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
            'last_message_direction'=>'recive'
        );
        $row_id = Whatsapp_conversations::create($conversation_data);
        return $row_id;
    }

  }
?>