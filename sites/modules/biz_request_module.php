<?php
	class Biz_requestModule extends Module{
        // to debug here, put in js console: help_debug_forms();

        public $add_models = array(
            "biz_categories"
            ,"siteBiz_forms"
            ,"cities"
            ,"siteBiz_requests"
            ,"siteUser_leads"
            ,"leads_complex");

        protected $lead_info;

        protected function add_spam_request($return_array, $reason, $form_info){
            
            $return_array['html'] = $this->controller->include_ob_view('biz_form/request_success_mokup.php');               
            $lead_info = array();
            if(isset($_REQUEST['biz'])){
                $lead_info = $_REQUEST['biz'];
                $lead_info['ip'] = $_SERVER['REMOTE_ADDR'];
            }

            $json_form = json_encode($form_info);
            $json_lead = json_encode($lead_info);
            $spam_row = array(
                'reason'=>$reason,
                'site_id'=>$this->controller->data['site']['id'],
                'form_info'=>$json_form,
                'lead_info'=>$json_lead,

            );
            TableModel::simple_create_by_table_name($spam_row,'biz_requests_spam');
            Leads_complex::update_page_convertions($form_info['page_id'],'spam_convertions');
            return $return_array;
        }

        public function enter_lead(){
            if(isset($_REQUEST['prevent_db_listing'])){
                print_r_help($_REQUEST,"the request");
                print_r_help($_SESSION,"the session");
            }
            $action_data = $this->action_data;
            $form_info = $this->controller->data['form_info'];
            $return_array = $action_data['return_array'];
           
            if(!$this->validate_multiple_requests()){
				$return_array['c-token'] = "904";
                return $this->add_spam_request($return_array, "multiple requests",$form_info);
            }

            if(!$form_info){
                $return_array['c-token'] = "907";
                return $this->add_spam_request($return_array, "empty request",array());
            }

            $return_array = $this->validate_request($return_array);

            

            if(!$return_array['success']){
				$return_array['c-token'] = "905";
                $reason = "invalid request unknown";
                if(isset($return_array['reason'])){
                    $reason = $return_array['reason'];
                }
                return $this->add_spam_request($return_array, $reason, $form_info);
            }

            $recapcha_valid = $this->validate_recapcha();
            if(!$recapcha_valid){
                if(isset($_REQUEST['prevent_db_listing'])){
                    print_help("skiping_recapcha",'skiping_recapcha');
                }
                else{
                    $return_array['c-token'] = "906";
                    return $this->add_spam_request($return_array, "recapcha invalid",$form_info);
                }
            }

            $have_meny_phone_duplications = $this->validate_phone_duplications($return_array);

        
            if(get_config('mode') == 'dev'){
                $have_meny_phone_duplications = false;
            }
            
            //create duplication mockup for spammers, with a success true result
            if($have_meny_phone_duplications){
                return $this->add_spam_request($return_array, "multiple phones",$form_info);
            }

           
            $this->lead_info['page_id'] = $form_info['page_id'];

            $this->add_cat_info_to_lead_info();
            $this->add_city_info_to_lead_info();

            $thanks_pixel = $form_info['thanks_pixel'];
            
            if($thanks_pixel != ""){
                foreach($this->lead_info as $lead_key=>$lead_val){

                    if(!is_array($lead_val)){
                        $thanks_pixel = str_replace('{{'.$lead_key.'}}',$lead_val,$thanks_pixel);
                    }
                }
            }
            
            $this->lead_info['thanks_pixel'] = $thanks_pixel;

            if(isset($_REQUEST['extra'])){
                $this->lead_info['extra'] = $_REQUEST['extra'];
            }

            $lead_sends_arr = Leads_complex::find_users_for_lead($this->lead_info);
            if(isset($_REQUEST['prevent_db_listing'])){
                print_help("preventing listing here!!!");                
                print_r_help($lead_sends_arr);               
                exit("preventing listing here!!!");
            }
            $this->lead_info['recivers'] = $lead_sends_arr['send_count'];

            $optional_fields = array(
                'ip',
                'cat_id',
                'c1',
                'c2',
                'c3',
                'c4',
                'city_id',
                'site_id',
                'page_id',
                'form_id',
                'is_mobile',
                'banner_id',
                'cube_id',
                'aff_id',
                'referrer',
                'site_ref',
                'recivers',
                'full_name',
                'phone',                
                'note',
                'extra_info',
                'campaign_type',
                'campaign_name',
            );

            $fixed_db_values = array();
            foreach ($optional_fields as $field){
                if(isset($this->lead_info[$field])){
                    $fixed_db_values[$field] = $this->lead_info[$field];
                }
            }

            $request_id = SiteBiz_requests::create($fixed_db_values);
            Leads_complex::update_page_convertions($form_info['page_id']);
            $this->lead_info['reuqest_id'] = $request_id;
            if(isset($fixed_db_values['banner_id']) && $fixed_db_values['banner_id'] != ''){
                $this->controller->add_model('siteNet_banners');
                SiteNet_banners::add_count_to_banner($fixed_db_values['banner_id'], 'convertions');
            }

            if(isset($fixed_db_values['cube_id']) && $fixed_db_values['cube_id'] != ''){
                $this->controller->add_model('siteSupplier_cubes');
                SiteSupplier_cubes::add_count_to_cube($fixed_db_values['cube_id'], 'convertions');
            }

            $this->send_leads_to_users($request_id,$fixed_db_values,$lead_sends_arr);

            $return_array['html'] = $this->controller->include_ob_view('biz_form/request_success.php',$this->lead_info);
            $have_redirect = false;
            if($form_info['thanks_redirect'] != ""){
                $have_redirect = true;
                $return_array['redirect_to'] = $form_info['thanks_redirect'];
            }
            $return_array['have_redirect'] = $have_redirect;
            return $return_array;
        }


        public function enter_lead_by_api(){

            $action_data = $this->action_data;
            $form_info = $this->controller->data['form_info'];
            $return_array = $action_data['return_array'];


            $this->lead_info = $action_data['lead_info'];

            $have_meny_phone_duplications = $this->validate_phone_duplications($return_array);
            
            //create duplication mockup for spammers, with a success true result
            if($have_meny_phone_duplications && !$this->lead_info['full_name'] == 'yacov avr'){
                $_REQUEST['biz'] = $this->lead_info;
                return $this->add_spam_request($return_array, "multiple phones",$form_info);
            }

            $this->lead_info['page_id'] = $form_info['page_id'];

            $this->add_cat_info_to_lead_info();
            $this->add_city_info_to_lead_info();

            $thanks_pixel = $form_info['thanks_pixel'];
            
            if($thanks_pixel != ""){
                foreach($this->lead_info as $lead_key=>$lead_val){

                    if(!is_array($lead_val)){
                        $thanks_pixel = str_replace("{{$lead_key}}",$lead_val,$thanks_pixel);
                    }
                }
            }
            $thanks_pixel = str_replace("<","--",$thanks_pixel);
            $this->lead_info['thanks_pixel'] = $thanks_pixel;



            $lead_sends_arr = Leads_complex::find_users_for_lead($this->lead_info);

            $this->lead_info['recivers'] = $lead_sends_arr['send_count'];

            $optional_fields = array(
                'ip',
                'cat_id',
                'c1',
                'c2',
                'c3',
                'c4',
                'city_id',
                'site_id',
                'page_id',
                'form_id',
                'is_mobile',
                'banner_id',
                'cube_id',
                'aff_id',
                'referrer',
                'site_ref',
                'recivers',
                'full_name',
                'phone',                
                'note',
                'extra_info',
                'campaign_type',
                'campaign_name',
            );

            $fixed_db_values = array();
            foreach ($optional_fields as $field){
                if(isset($this->lead_info[$field])){
                    $fixed_db_values[$field] = $this->lead_info[$field];
                }
            }

            $request_id = SiteBiz_requests::create($fixed_db_values);
            Leads_complex::update_page_convertions($form_info['page_id']);
            $this->lead_info['reuqest_id'] = $request_id;

            $this->send_leads_to_users($request_id,$fixed_db_values,$lead_sends_arr);

            return $return_array;
        }


        protected function validate_multiple_requests(){
            $biz_request_count = 0;
            if(session__isset('biz_request_count')){
                $biz_request_count = session__get('biz_request_count');
            }
            $biz_request_count++;
            session__set('biz_request_count',$biz_request_count);
            if($biz_request_count > 2){
                if(!session__isset('biz_unlimited_count')){
                    return false;
                }
            }
            return true;
        }

        protected function validate_recapcha(){
            $global_settings_add_capcha = get_config('add_capcha');
            $add_capcha = false;
            if(
                $global_settings_add_capcha == '1' && 
                $this->controller->data['site']['use_recapcha'] == '1' && 
                $this->controller->data['site']['recapcha_public'] != "" && 
                $this->controller->data['site']['recapcha_secret'] != ""
            ){
                $add_capcha = true;
            }

            if(!$add_capcha){
                return true;
            }

            $recaptcha_secret = $this->controller->data['site']['recapcha_secret'];
            $recaptcha_response = $_REQUEST['g_recaptcha_token'];
            $score_threshold = 0.5; // Set a threshold score here

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = array(
                'secret' => $recaptcha_secret,
                'response' => $recaptcha_response
            );

            $options = array(
                'http' => array (
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content'=> http_build_query($data)
                )
            );

            $context  = stream_context_create($options);
            $response = file_get_contents($url, false, $context);
            $result = json_decode($response, true);

            if ($result['success'] && $result['score'] >= $score_threshold) {
                // User is likely human - perform form submission logic here
                return true;
            } else {
                // User is likely a bot or suspicious - take appropriate action here
                return false;
            }
        }

        protected function validate_request($return_array){
            
            if(!filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)){
                $return_array['success'] = false;
                $return_array['reason'] = "invalid IP address";
                $return_array['error'] = array('msg'=>__tr("Error accured. Please reload and try again"));
                return $return_array;
            }


            else{
                $blocked_ips = get_config('blocked_ips');
                if(str_contains($blocked_ips, $_SERVER['REMOTE_ADDR'])){
                    $return_array['success'] = false;
                    $return_array['reason'] = "IP blocked";
                    $return_array['error'] = array('msg'=>__tr("Error accured. Please reload and try again"));
                    return $return_array;
                }
            }
            
            if(!isset($_REQUEST['biz']) || ! is_array($_REQUEST['biz'])){
                $return_array['success'] = false;
                $return_array['reason'] = "empty request";
                $return_array['error'] = array('msg'=>__tr("Error accured. Please reload and try again"));
                return $return_array;
            }

            if(isset($_REQUEST['biz']['phone'])){
                $blocked_phones = get_config('blocked_phones');
                if(str_contains($blocked_phones, $_REQUEST['biz']['phone'])){
                    $return_array['success'] = false;
                    $return_array['reason'] = "phone blocked";
                    $return_array['error'] = array('msg'=>__tr("Error accured. Please reload and try again"));
                    return $return_array;
                }
            }

            $this->lead_info = $_REQUEST['biz'];
            $this->lead_info['ip'] = $_SERVER['REMOTE_ADDR'];
            
            $fields_collection = SiteBiz_requests::setup_field_collection();
            
            if(isset($this->controller->data['let_phone_go'])){
                $fields_collection['phone']['validation'] = 'required';
            }
            $form_info = $this->controller->data['form_info'];
            $input_remove_arr = $form_info['input_remove_arr'];

            foreach($fields_collection as $field_key=>$field){
                
                if(in_array($field_key, $input_remove_arr)){
                    unset($fields_collection[$field_key]);
                    continue;
                }
                
                if(!isset($_REQUEST['biz'][$field_key])){
                    $return_array['success'] = false;
                    $return_array['reason'] = "missing field: ".$field_key;
                    $return_array['error'] = array('msg'=>__tr("Error accured. Please reload and try again"));
                    return $return_array;
                }
            }

            $form_handler = $this->controller->init_form_handler("biz_request");
            $form_handler->update_fields_collection($fields_collection);
            $validate_result = $form_handler->validate('biz');

            if(!$validate_result['success']){
                $return_array['success'] = false;
                $return_array['reason'] = "invalid fields";
                $return_array['error'] = array('msg'=>$validate_result['err_messages']);
                return $return_array;
            }

            if(isset($_REQUEST['biz']['extra'])){
                $this->lead_info['extra_info'] = json_encode($_REQUEST['biz']['extra']);
            }
            else{
                $this->lead_info['extra_info'] = "";
            }
            //now good
            return $return_array;

        }

        protected function validate_phone_duplications($return_array){
            $phone = $this->lead_info['phone'];
            if($phone == "0542323232"){
                return false;
            }
            if($phone == ""){
                return true;
            }
            $weekly_phone_duplications = SiteBiz_requests::count_weekly_phone_duplications($phone);
            
            if($weekly_phone_duplications < 3){
                
                return false;
            }
            
            return true;
        }

        protected function add_cat_tree_to_db_values(){
            $cat_tree = $this->lead_info['cat_tree'];
            $optional_values = array(
                '0'=>'c1',
                '1'=>'c2',
                '2'=>'c3',
                '3'=>'c4'
            );
            foreach($cat_tree as $cat_key=>$cat){
                $field_key = $optional_values[$cat_key];
                $this->lead_info[$field_key] = $cat['id'];
            }
        }

        protected function add_cat_info_to_lead_info(){
            $cat_tree = Biz_categories::simple_get_item_parents_tree($this->lead_info['cat_id'],"*");
            $this->lead_info['cat_tree'] = $cat_tree;
            $this->add_cat_tree_to_db_values();

            $cat_tree_name_arr = array();
            foreach($this->lead_info['cat_tree'] as $cat){
                $cat_tree_name_arr[] = $cat['label']; 
            }
            $cat_tree_name = implode(", ",$cat_tree_name_arr);
            $this->lead_info['cat_tree_name'] = $cat_tree_name;
        }

        protected function add_city_info_to_lead_info(){
            if($this->lead_info['city_id'] == '0' || $this->lead_info['city_id'] == ''){
                $main_city = Cities::find(array('parent'=>'0'));
                if($main_city && isset($main_city['id'])){
                    $this->lead_info['city_id'] = $main_city['id'];
                }
            }
            $city_offsrings = Cities::simple_get_item_offsprings($this->lead_info['city_id'],"id");
            $this->lead_info['city_offsrings'] = $city_offsrings;
            $city_tree = Cities::simple_get_item_parents_tree($this->lead_info['city_id'],"*");
            $this->lead_info['city_tree'] = $city_tree;

            $city_name = "";
            foreach($this->lead_info['city_tree'] as $city){
                if($this->lead_info['city_id'] == $city['id'])
                $city_name = $city['label'];
            }
            $this->lead_info['city_name'] = $city_name;
        }

        protected function send_user_lead_api($user_id,$lead_info){
            //the lead info is like the email info with *** on phone when no leads credit
            $filter = array('user_id'=>$user_id);
            $api_sends = TableModel::simple_get_list_by_table_name($filter,'user_lead_api');
            if(!$api_sends){
                return;
            }
            if(empty($api_sends)){
                return;
            }
            foreach($api_sends as $api_send){
                $api_url = $api_send['url'];
                foreach($lead_info as $key=>$val){
                    if(!is_array($val)){
                        $api_url = str_replace('{{'.$key.'}}',$val,$api_url);
                    }
                }

                if($api_send['custom_replace'] != ''){
                    $custom_replace = json_decode($api_send['custom_replace'],true);
                    foreach($custom_replace as $custom_key=>$custom_arr){
                        $custom_key_val = $lead_info[$custom_arr['key']];
                        if(isset($custom_arr['values'][$custom_key_val])){
                            $custom_key_search = '{{'.$custom_key.$custom_key_val.'}}';
                            $custom_key_replace = $custom_arr['values'][$custom_key_val];
                            $api_url = str_replace($custom_key_search,$custom_key_replace,$api_url);
                        }
                    }
                }
                //break the url and params for the curl, remove the first ? from params 
                //but if some parameter has a ? sign and we explode by mistake so return it
                $url_arr = explode("?",$api_url);
                $url = $url_arr[0];
                $params = "";
                for($i=1;$i<count($url_arr);$i++){
                    if($i!=1){
                        $params.="?";
                    }
                    $params.=$url_arr[$i];
                }

                $ch = curl_init(); 
                curl_setopt( $ch, CURLOPT_URL,$url ); 
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt( $ch, CURLOPT_POST, 1 ); 
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $params ); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                $result = curl_exec ($ch); 
                
                Helper::add_log("api_log.txt","\nAPI RESULT:".$result);

                curl_close ($ch);
        
              }
        }

        protected function send_leads_to_users($request_id,$fixed_db_values,$lead_sends_arr){
            $duplicate_user_leads = $lead_sends_arr['duplicate_user_leads'];
            $this->controller->add_model('user_pending_emails');
            foreach($lead_sends_arr['users'] as $user_id=>$user){
                $duplicate_lead = false;

                if(isset($duplicate_user_leads[$user['info']['id']])){
                    $duplicate_lead = $duplicate_user_leads[$user['info']['id']];
                }

                $user_lead_settings = $user['lead_settings'];
                $token = md5(time().$this->lead_info['phone']);

                $db_lead_info = $this->lead_info;
                $email_lead_info = $this->lead_info;

                $email_lead_info['alert_leads_credit'] = false;

                $open_mode_final = false;
                $user_lead_credit = intval($user_lead_settings['lead_credit']);
                $billed = '0';
                if($user_lead_settings['open_mode']){ 
                    if($user_lead_credit > 0){
                        $open_mode_final = true;
                        if($duplicate_lead){
                            $billed = '0';
                        }
                        else{
                            $billed = '1';
                        }
                    }
                    else{
                        if($user_lead_settings['free_send']){
                            $open_mode_final = true;
                            if($duplicate_lead){
                                $billed = '0';
                            }
                            else{
                                $billed = '1';
                            }
                        }
                        else{
                            $email_lead_info['alert_leads_credit'] = true;
                        }								
                    }							
                }

                if(!$open_mode_final){
                    $email_lead_info['phone'] = substr_replace( $email_lead_info['phone'] , "****" , 4 , 4 );
                    $email_lead_info['email'] = '****@****';
                }  

                $this->send_user_lead_api($user_id,$email_lead_info);

                $db_lead_info['open_mode_final'] = $open_mode_final;
                $db_lead_info['open_state'] = $open_mode_final? '1' : '0';
                $db_lead_info['token'] = $token;
                $db_lead_info['request_id'] = $request_id;
                $db_lead_info['billed'] = $billed;
                $db_lead_info['duplicate_id'] = $duplicate_lead ? $duplicate_lead : "";
                $db_lead_info['send_state'] = '0';
                $db_lead_info['resource'] = 'form';
                
                
                $user_lead_id = SiteUser_leads::add_user_lead($db_lead_info,$user);

                $auth_link = get_config('master_url')."/myleads/leads/auth/?token=".$token."&lead=".$user_lead_id."&user=".$user_id;
                

                $email_info = array(
                    'lead'=>$email_lead_info,
                    'user'=>$user,
                    'site'=>$this->controller->data['site'],
                    'auth_link'=>$auth_link,
                    'lead_id'=>$user_lead_id
                );

                $email_content = $this->controller->include_ob_view('emails_send/user_lead_alert.php',$email_info);
                

                $sms_content = $this->controller->include_ob_view('emails_send/user_lead_sms_alert.php',$email_info);
                
                $user_send_times = Leads_complex::get_user_send_times($user_id);
                $email_pending_message = array(
                    'user_id'=>$user['info']['id'],
                    'email_to'=>$user['info']['email'],
                    'phone_to'=>$user['info']['phone'],
                    'title'=>__tr("Quote request from the website"),
                    'content'=>$email_content,
                    'sms_content'=>$sms_content,
                    'send_times'=>$user_send_times,
                    'lead_id'=>$user_lead_id
                );
                $send_email = false;
                $send_sms = false;
                
                if($user['lead_visability']){
                    
                    $send_email = $user['lead_visability']['send_lead_email_alerts'];
                    $send_sms = $user['lead_visability']['send_lead_sms_alerts'];
                }
                if(!$send_sms == '1'){

                    $email_pending_message['sms_content'] = "";
                }
                if(!$send_email){
                    $email_pending_message['content'] = "";
                }
                
                User_pending_emails::create($email_pending_message);
                
            }
        }
	}
?>