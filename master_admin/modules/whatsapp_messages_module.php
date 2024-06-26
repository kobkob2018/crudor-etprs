<?php
/*
for token changes: 
https://graph.facebook.com/v12.0/oauth/access_token?  
    grant_type=fb_exchange_token&           
    client_id=[your-app-id]&
    client_secret=[your-app-secret]&
    fb_exchange_token=[your-short-term-token]


*/

  class Whatsapp_messagesModule extends Module{

    public $add_models = array("whatsapp_settings", "whatsapp_conversations", "whatsapp_messages","biz_categories","cities");

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
            'message_time'=>date('Y-m-d H:i:s'),
            'image_link'=>$message_data['image_link'],
            'video_link'=>$message_data['video_link'],
            'message_text'=>$message_data['message_text'],
            'message_type'=>$message_data['message_type'],
            'direction'=>'send',
            'log'=>$message_send['log'],
            'wamid'=>$message_send['messages'][0]['id']
        );
        $message_id = Whatsapp_messages::create($message_row_data);
        $conversation_update = array(
            'last_message_id'=>$message_id,
            'last_message_time'=>date('Y-m-d H:i:s'),
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
        if($message_data['message_type'] == 'image'){
            $data['image'] = array(
                "link"=> $message_data['image_link'],
            );
            if($message_data['message_text'] != ''){
                $data['image']['caption'] = $message_data['message_text'];
            }
        }

        if($message_data['message_type'] == 'video'){
            $data['video'] = array(
                "link"=> $message_data['video_link'],
            );
            if($message_data['message_text'] != ''){
                $data['video']['caption'] = $message_data['message_text'];
            }
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
        $result_arr['log'] = $result;
        return $result_arr;
    }

    public function check_for_error_notifications($message_info){
        
        if(
            (!isset($message_info['entry'][0])) ||
            (!isset($message_info['entry'][0]['changes'][0])) ||
            (!isset($message_info['entry'][0]['changes'][0]['value'])) || 
            (!isset($message_info['entry'][0]['changes'][0]['value']['statuses'])) ||
            (!isset($message_info['entry'][0]['changes'][0]['value']['statuses'][0])) ||
            (!isset($message_info['entry'][0]['changes'][0]['value']['statuses'][0]['id'])) ||
            (!isset($message_info['entry'][0]['changes'][0]['value']['statuses'][0]['errors'])) ||
            (!isset($message_info['entry'][0]['changes'][0]['value']['statuses'][0]['errors'][0]))
        ){
            return false;
        } 

        $wamid = $message_info['entry'][0]['changes'][0]['value']['statuses'][0]['id'];
        $message_row = Whatsapp_messages::find(array('wamid'=>$wamid));
        if(!$message_row){
            return;
        }
        $update_arr = array('send_state'=>'error');
        $error = $message_info['entry'][0]['changes'][0]['value']['statuses'][0]['errors'][0];
        if(isset($error['error_data'])){
            if(isset($error['error_data']['details'])){
                $update_arr['error_msg'] = $error['error_data']['details'];
            }
        }

        Whatsapp_messages::update($message_row['id'],$update_arr);
        $err_message_row = array(
            'conversation_id'=>$message_row['conversation_id'],
            'message_id'=>$message_row['id'],
            'message_wamid'=>$wamid,
            'error_msg'=>$error['error_data']['details']
        );
        Whatsapp_messages::simple_create_by_table_name($err_message_row,'whatsapp_messages_errors');
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
            $this->check_for_error_notifications($message_info);
            return false;
        }
        
        $metadata = $message_info['entry'][0]['changes'][0]['value']['metadata'];
        $contact = $message_info['entry'][0]['changes'][0]['value']['contacts'][0];
        $message = $message_info['entry'][0]['changes'][0]['value']['messages'][0];
        $self_phone = $metadata['display_phone_number'];
        $contact_phone = $contact['wa_id'];
        $connection_id = $self_phone."_".$contact_phone;
        
        $filter_arr = array("connection_id"=>$connection_id);
        $payload = array(
            'order_by'=>'id desc'
        );
        $conversation_row = Whatsapp_conversations::find($filter_arr,'*',$payload);
        $conversation_id = false;
        $lead_info = array(
            'full_name'=>$contact['profile']['name'],
            'phone'=>$contact['wa_id'],
            'page_id'=>'0',
            'form_id'=>'0',
            'parent_cat_id'=>'0',
            'cat_id'=>'0',
            'city_id'=>'0'
        );
        if(!$conversation_row){   
            //create new conversation
            $conversation_id = $this->add_conversation($metadata,$contact,$message,$connection_id,$lead_info);
            $conversation_row = Whatsapp_conversations::get_by_id($conversation_id);
            $bot_state = json_decode($conversation_row['bot_state'],true);
        }
        else{
            $last_lead_info = json_decode($conversation_row['lead_info'],true);
            $bot_state = json_decode($conversation_row['bot_state'],true);
            //continue conversation or renew conversation
            $conversation_id = $conversation_row['id'];
            if($conversation_row['stage'] == 'open'){
                //
                
                $conversation_abandoned = false;
                if($conversation_row['last_message_time'] != ''){
                    $days_pass = time() - strtotime($conversation_row['last_message_time']); // in seconds
                    if($days_pass!=0){
                        $days_pass = $days_pass/24/60/60;
                    }
                    if($days_pass>2){
                        

                        $previous_conversations_json = $conversation_row['previous_conversations'];
                        $previous_conversations = array();
                        if($previous_conversations_json != ""){
                            $previous_conversations = json_decode($previous_conversations_json,true);
                        }
                        $previous_conversations[] = array(
                            'stage'=>'abandoned',
                            'lead_info'=>json_encode($lead_info)
                        );
                        $previous_conversations_json_update = json_encode($previous_conversations);


                        $conversation_update = array(
                            'previous_conversations'=>$previous_conversations_json_update,
                            'lead_info'=>json_encode($lead_info)
                        );
                        Whatsapp_conversations::update($conversation_row['id'],$conversation_update);
                        $conversation_abandoned = true;
                        $conversation_row = Whatsapp_conversations::get_by_id($conversation_row['id']);
                    }
                }
                if(!$conversation_abandoned){
                    $lead_info = $last_lead_info;
                }
            }
            else{
                $conversation_update = array(
                    'lead_info'=>json_encode($lead_info),
                    'stage'=>'open'
                );
                Whatsapp_conversations::update($conversation_id,$conversation_update);
            }
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

            $wamid_message = Whatsapp_messages::find($wamid_filter);
            
            if($wamid_message){
                if($sender_is_admin){
                    $message_is_reply_from_admin = true;
                    $conversation_id = $wamid_message['conversation_id'];
                    $conversation_row = Whatsapp_conversations::get_by_id($conversation_id);
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
            'message_time'=>date('Y-m-d H:i:s',$message_time),
            'message_type'=>$message_type,
            'message_text'=>$message_text,
            'direction'=>$direction,
            'log'=>$message_data['message_info'],
            'wamid'=>$message['id']
        );
        
        if(!$message_is_reply_from_admin){
            if($wamid_message){
                $message_row_data['context'] = $wamid_message['id'];
            }
        }
        
        $message_id = Whatsapp_messages::create($message_row_data);
        $message_row_data['id'] = $message_id;
        if($message_is_reply_from_admin){
            $this->foreword_message_from_admin($conversation_row,$message_row_data);
        }
        
        if($direction=='recive'){
            if($bot_state['admin_alerts'] == '1'){
                $this->send_alert_to_admin($conversation_row,$message_row_data);
            }
        }

        $reply_sent = false;
        $info_changed = false;
        $new_search_options_found = false;
        if($bot_state['info_collect'] == '1'){ 
            if($form_info = $this->track_form_from_message_text($message_text)){
                $info_changed = true;
                $lead_info['page_id'] = $form_info['page_id'];
                $lead_info['form_id'] = $form_info['form_id'];
                $cat_id = $form_info['cat_id'];
                if(isset($form_info['cat_options'])){
                    
                    $lead_info['cat_id_options'] = $form_info['cat_id_options'];
                    $lead_info['cat_options'] = $form_info['cat_options'];
                    
                    if(count($lead_info['cat_id_options']) == '1'){
                        $cat_id = $lead_info['cat_id_options'][0];
                        $lead_info['cat_id'] = $cat_id;
                    }
                    else{
                        $new_search_options_found = true;
                        $cat_id = '0';
                    }
                }
                if($new_search_options_found){

                }
                elseif($cat_children = $this->fetch_cat_children($cat_id)){
                    Helper::add_log("watsap.txt","\n\n INSIDE 1-1 \n\n");
                    $lead_info['parent_cat_id'] = $cat_id;
                    $lead_info['cat_id'] = '0';
                }
                else{
                    Helper::add_log("watsap.txt","\n\n INSIDE 1-2 \n\n");
                    $lead_info['cat_id'] = $cat_id;
                    $biz_category = Biz_categories::get_by_id($lead_info['cat_id'],'id, add_city_to_whatsap');
                    if($biz_category && $biz_category['add_city_to_whatsap'] == '0'){
                        $lead_info['city_id'] = '1';
                    }
                }
            }
            elseif($city_id = $this->track_city_from_message_text($message_text)){
                $lead_info['city_id'] = $city_id;
            }
        }

        if($bot_state['auto_reply'] == '1'){
            if($new_search_options_found){
                $cat_options = $lead_info['cat_options'];
                $this->send_cat_options_by_search_to_contact($conversation_row ,$cat_options);
                $reply_sent = true;
            }
            elseif($lead_info['cat_id'] == '0'){
                $cat_children = $this->fetch_cat_children($lead_info['parent_cat_id']);
                $this->send_cat_request_to_contact($conversation_row, $lead_info['parent_cat_id'] ,$cat_children);
                $reply_sent = true;
            }
            elseif($lead_info['city_id'] == '0'){
                $city_request_type = "city_select_message";
                if(!$info_changed){
                    $city_request_type = "city_correct_message";
                }
                $this->send_city_request_to_contact($conversation_row, $lead_info['cat_id'], $city_request_type);
                $reply_sent = true;
            }
        }

        $lead_info_json = json_encode($lead_info);        
        $conversation_update = array(
            'lead_info'=>$lead_info_json
        );
        if(!$reply_sent){
            $conversation_update['last_message_id'] = $message_id;
            $conversation_update['last_message_time'] = date('Y-m-d H:i:s',$message_time);
        }

        if($lead_info['city_id'] != '0' && $lead_info['cat_id'] != '0'){
            $conversation_update['stage'] = 'closed';
            $previous_conversations_json = $conversation_row['previous_conversations'];
            $previous_conversations = array();
            if($previous_conversations_json != ""){
                $previous_conversations = json_decode($previous_conversations_json,true);
            }
            $previous_conversations[] = array(
                'stage'=>'closed',
                'lead_info'=>$lead_info
            );
            $previous_conversations_json_update = json_encode($previous_conversations);
            $conversation_update['previous_conversations'] = $previous_conversations_json_update;
        } 
        Whatsapp_conversations::update($conversation_id,$conversation_update);
        if($lead_info['city_id'] != '0' && $lead_info['cat_id'] != '0'){
            $this->add_lead($conversation_id);
            $this->send_lead_confirm_message($conversation_row,$lead_info['cat_id'],$lead_info['city_id']);
        }
    }

    protected function send_cat_request_to_contact($conversation_data,$cat_id,$cat_children,$cat_message_id = 'cat_select_message'){

        $biz_category = Biz_categories::get_by_id($cat_id,'label');

        $cat_name = "במגוון תחומים";
        if($biz_category){
            $cat_name = $biz_category['label'];
        }
        $cat_children_text = "";
        foreach($cat_children as $cat){
            $cat_children_text .= "\n".$cat['label'];
        }

        $message_text = Whatsapp_settings::get()[$cat_message_id];
        $message_text = str_replace("{{cat_name}}",$cat_name,$message_text);
        $message_text = str_replace("{{cat_list}}",$cat_children_text,$message_text);

        $message_data = array(
            'message_type'=>'text',
            'message_text'=>$message_text,
        );
        return $this->send_reply_to_contact($conversation_data,$message_data);
    }

    protected function send_cat_options_by_search_to_contact($conversation_data ,$cat_options){
        $cat_options_text = "";
        foreach($cat_options as $cat){
            $cat_options_text .= "\n".$cat['label'];
        }

        $message_text = "אנא בחר אחת מן האפשרויות: {{cat_list}}"; //Whatsapp_settings::get()[$cat_message_id];
        
        $message_text = str_replace("{{cat_list}}",$cat_options_text,$message_text);

        $message_data = array(
            'message_type'=>'text',
            'message_text'=>$message_text,
        );
        return $this->send_reply_to_contact($conversation_data,$message_data);
    }

    protected function fetch_cat_children($cat_id){
        $cat_list_filter = array('parent'=>$cat_id,'active'=>'1','visible'=>'1');
        $cat_list = Biz_categories::get_list($cat_list_filter,'id, parent, label');
        if(empty($cat_list)){
            return false;
        }
        return $cat_list;
    }

    protected function send_lead_confirm_message($conversation_data,$cat_id,$city_id){
        $biz_category = Biz_categories::get_by_id($cat_id,'label');
        $city = Cities::get_by_id($city_id,'label');
        $message_text = Whatsapp_settings::get()['lead_confirm_message'];
        $message_text = str_replace("{{cat_name}}",$biz_category['label'],$message_text);
        $message_text = str_replace("{{city_name}}",$city['label'],$message_text);
        $message_data = array(
            'message_type'=>'text',
            'message_text'=>$message_text,
        );
        return $this->send_reply_to_contact($conversation_data,$message_data);
    }

    protected function send_city_request_to_contact($conversation_data,$cat_id,$city_message_id = 'city_select_message'){

        $this->controller->add_model('user_cat_city');
        $biz_category = Biz_categories::get_by_id($cat_id,'label');
        $allowed_cities = User_cat_city::get_cat_city_assign($cat_id);
        
        if(empty($allowed_cities)){
            $city_list = Cities::get_flat_select_city_options();
        }
        else{
            $city_list = Cities::get_filtered_flat_select_city_options($allowed_cities);
        }

        $city_list_text = "";
        foreach($city_list as $city){
            $city_list_text .= "\n".$city['label'];
        }

        $message_text = Whatsapp_settings::get()[$city_message_id];
        $message_text = str_replace("{{cat_name}}",$biz_category['label'],$message_text);
        $message_text = str_replace("{{city_list}}",$city_list_text,$message_text);

        $message_data = array(
            'message_type'=>'text',
            'message_text'=>$message_text,
        );
        return $this->send_reply_to_contact($conversation_data,$message_data);
    }

    protected function send_reply_to_contact($conversation_data,$message_data){

        $message_send = $this->send_message_with_api($conversation_data,$message_data);
        if(isset($message_send['error'])){
            SystemMessages::add_err_message($message_send['error']['message']);
            if(isset($message_send['error']['error_data'])){
                SystemMessages::add_err_message($message_send['error']['error_data']['details']);
            }
            return $this->controller->redirect_to(inner_url("whatsapp_messages/add/?conversation_id=".$conversation_id));
        }
        $connection_id = $conversation_data['connection_id'];
        $conversation_id = $conversation_data['id'];
        $message_row_data = array(
            'conversation_id'=>$conversation_id,
            'connection_id'=>$connection_id,
            'message_time'=>date('Y-m-d H:i:s'),
            'message_text'=>$message_data['message_text'],
            'message_type'=>$message_data['message_type'],
            'direction'=>'send',
            'log'=>$message_send['log'],
            'wamid'=>$message_send['messages'][0]['id']
        );
        $message_id = Whatsapp_messages::create($message_row_data);
        $conversation_update = array(
            'last_message_id'=>$message_id,
            'last_message_time'=>date('Y-m-d H:i:s'),
        );
        Whatsapp_conversations::update($conversation_id,$conversation_update);
    }

    public function send_lead_by_curl($lead_info){
        
        $api_key = get_config("curl_key");
        $url = "https://il-biz.co.il/biz_form/submit_request_by_curl/";



        $data = array(
            'cat_id'=> $lead_info['cat_id'],
            "city_id"=> $lead_info['city_id'],
            "page_id"=> $lead_info['page_id'],
            "form_id"=> $lead_info['form_id'],
            "full_name"=> $lead_info['full_name'],
            "phone"=> $lead_info['phone'],
        );
        


        $payload = json_encode($data);

        $ch = curl_init($url);
        curl_setopt( $ch, CURLOPT_POST, 1 ); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer '.$api_key));
        $result = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);
        return $result;
    }


    protected function add_lead($conversation_id){
        $conversation_row = Whatsapp_conversations::get_by_id($conversation_id);
        $lead_info = json_decode($conversation_row['lead_info'],true);
        return $this->send_lead_by_curl($lead_info);
    }

    protected function track_form_from_message_text($message_text){
        $message_arr = explode('"',$message_text);
        if(!isset($message_arr[1])){
            return $this->track_cat_from_message_text($message_text);
        }
        
        $search_term = $message_arr[1];
        $page_filter = array('title'=>$search_term);
        $page_find = TableModel::simple_find_by_table_name($page_filter,'content_pages','id');
        if(!$page_find){
            return $this->track_cat_from_message_text($message_text);
        }
        $page_id = $page_find['id'];
        $form_filter = array('page_id'=>$page_id);
        $form_find = TableModel::simple_find_by_table_name($form_filter,'biz_forms');
        if(!$form_find){
            return false;
        }
        return array(
            'page_id'=>$page_id,
            'form_id'=>$form_find['id'],
            'cat_id'=>$form_find['cat_id']
        );
    }


    protected function track_cat_matches_with_message_text($message_text){
        
        $maching_cats = Biz_categories::find_matches_with($message_text);
        if(empty($maching_cats)){
            
            return false;
        }
        $cat_id_options = array();
        foreach($maching_cats as $cat){
            
            $cat_id_options[] = $cat['id'];
        }
        
        return array(
            'cat_id'=>'0',
            'page_id'=>'0',
            'form_id'=>'0',
            'cat_options'=>$maching_cats,
            'cat_id_options'=>$cat_id_options,
        );
    }



    protected function track_cat_from_message_text($message_text){
        $cat_vag_terms= Whatsapp_settings::get()['cat_vag_terms'];
        if (strpos($cat_vag_terms, $message_text) !== false) {
            return false;
        }
        $search_term = $message_text;

        $cat_filter = array(
            'cat_like'=>array(
                'str_like'=>$search_term, 
                'columns_like'=>array('label'))
        );

        $cat_find = Biz_categories::find($cat_filter,'id');
        if(!$cat_find){
            return $this->track_cat_matches_with_message_text($message_text);
            return false;
        }
        $cat_id = $cat_find['id'];
        $form_filter = array('cat_id'=>$cat_id);
        $form_find = TableModel::simple_find_by_table_name($form_filter,'biz_forms');
        if(!$form_find){
            $form_find = array(
                'id'=>'0',
                'page_id'=>'0'
            );
        }
        return array(
            'page_id'=>$form_find['page_id'],
            'form_id'=>$form_find['id'],
            'cat_id'=>$cat_id
        );
    }

    protected function track_city_from_message_text($message_text){
        $cat_vag_terms= Whatsapp_settings::get()['city_vag_terms'];
        if (strpos($cat_vag_terms, $message_text) !== false) {
            return false;
        }
        Helper::add_log('meta_webhooks_admin.txt',$message_text.": tracking city");
        $city_filter = array('label'=>$message_text);//
        $city_filter = array(
            'city_like'=>array(
                'str_like'=>$message_text, 
                'columns_like'=>array('label'))
        );
        $city_find = Cities::find($city_filter,'id');
        if(!$city_find){
            Helper::add_log('meta_webhooks_admin.txt',": CIty not found");
            return false;
        }
        Helper::add_log('meta_webhooks_admin.txt',": CIty YES found");
        return $city_find['id'];
    }


    protected function foreword_message_from_admin($conversation_row,$message_row_data){
        return $this->send_message_with_api($conversation_row,$message_row_data);
    }

    protected function send_alert_to_admin($conversation_data, $message_row_data){
        
        $to = Whatsapp_settings::get()['admin_alerts_phone'];
        $sms_to = Whatsapp_settings::get()['admin_alerts_sms_phone'];
        $message_text = "message from system: \n\n";
        $message_text .= "connection_id: ".$message_row_data['connection_id']."\n";
        $message_text .= "message: \n";
        $message_text .= $message_row_data['message_type'].": \n".$message_row_data['message_text'];
        $message_text .= "\n\nview in admin: ".outer_url('whatsapp_messages/add/?conversation_id='.$conversation_data['id']);
        Helper::send_sms($sms_to,$message_text);
        $message_data = array(
            'message_type'=>'text',
            'message_text'=>$message_text,
        );
        $message_send = $this->send_message_with_api($conversation_data,$message_data,$to);
        $admin_wamid = $message_send['messages'][0]['id'];
        Whatsapp_messages::update($message_row_data['id'],array('admin_wamid'=>$admin_wamid));
    }

    protected function add_conversation($metadata,$contact,$message,$connection_id,$lead_info){
        $bot_state = array(
            'auto_reply'=>'1',
            'info_collect'=>'1',
            'admin_alerts'=>'1'
        );
        $conversation_data = array(
            'connection_id'=>$connection_id,
            'owner_phone'=>$metadata['display_phone_number'],
            'owner_phone_id'=>$metadata['phone_number_id'],
            'contact_phone_wa_id'=>$contact['wa_id'],
            'contact_wa_name'=>$contact['profile']['name'],
            'contact_custom_name'=>"",
            'last_message_time'=>date('Y-m-d H:i:s',$message['timestamp']),
            'last_message_direction'=>'recive',
            'stage'=>'open',
            'lead_info'=>json_encode($lead_info),
            'bot_state'=>json_encode($bot_state)
        );
        $row_id = Whatsapp_conversations::create($conversation_data);
        return $row_id;
    }

  }
?>