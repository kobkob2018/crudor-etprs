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
        public function enter_lead(){
            $action_data = $this->action_data;
            $form_info = $this->controller->data['form_info'];
            $return_array = $action_data['return_array'];
           
            if(!$form_info){
                return;
            }

            $return_array = $this->validate_request($return_array);

            

            if(!$return_array['success']){
                return $return_array;
            }

            $have_meny_phone_duplications = $this->validate_phone_duplications($return_array);

        
            if(get_config('mode') == 'dev'){
                $have_meny_phone_duplications = false;
            }
            
            //create duplication mockup for spammers, with a success true result
            if($have_meny_phone_duplications){
                $return_array['html'] = $this->controller->include_ob_view('biz_form/request_success_mokup.php');
                return $return_array;
            }

           
            $this->lead_info['page_id'] = $form_info['page_id'];

            $this->add_cat_info_to_lead_info();
            $this->add_city_info_to_lead_info();

            $this->lead_info['thanks_pixel'] = $form_info['thanks_pixel'];
            

            if(isset($_REQUEST['extra'])){
                $this->lead_info['extra'] = $_REQUEST['extra'];
            }

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
                'aff_id',
                'referrer',
                'recivers',
                'full_name',
                'phone',                
                'note',
                'extra_info',
            );

            $fixed_db_values = array();
            foreach ($optional_fields as $field){
                if(isset($this->lead_info[$field])){
                    $fixed_db_values[$field] = $this->lead_info[$field];
                }
            }

            $request_id = SiteBiz_requests::create($fixed_db_values);

            if(isset($fixed_db_values['banner_id']) && $fixed_db_values['banner_id'] != ''){
                $this->controller->add_model('siteNet_banners');
                SiteNet_banners::add_count_to_banner($fixed_db_values['banner_id'], 'convertions');
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

        protected function validate_request($return_array){
            
            if(!filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)){
                $return_array['success'] = false;
                $return_array['error'] = array('msg'=>"אירעה שגיאה. אנא טען את הדף ונסה שוב");
                return $return_array;
            }

            
            if(!isset($_REQUEST['biz']) || ! is_array($_REQUEST['biz'])){
                $return_array['success'] = false;
                $return_array['error'] = array('msg'=>"אירעה שגיאה. אנא טען את הדף ונסה שוב");
                return $return_array;
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
                    $return_array['error'] = array('msg'=>"אירעה שגיאה. אנא טען את הדף ונסה שוב");
                    return $return_array;
                }
            }

            $form_handler = $this->controller->init_form_handler("biz_request");
            $form_handler->update_fields_collection($fields_collection);
            $validate_result = $form_handler->validate('biz');

            if(!$validate_result['success']){
                $return_array['success'] = false;
                $return_array['error'] = array('msg'=>$validate_result['err_messages']);
                return $return_array;
            }

            if(isset($_REQUEST['extra'])){
                $this->lead_info['extra_info'] = json_encode($_REQUEST['extra']);
            }
            else{
                $this->lead_info['extra_info'] = "";
            }
            //now good
            return $return_array;

        }

        protected function validate_phone_duplications($return_array){
            $phone = $this->lead_info['phone'];
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
            $city_tree = Cities::simple_get_item_parents_tree($this->lead_info['city'],"*");
            $this->lead_info['city_tree'] = $city_tree;

            $city_name = "";
            foreach($this->lead_info['city_tree'] as $city){
                if($this->lead_info['city'] == $city['id'])
                $city_name = $city['label'];
            }
            $this->lead_info['city_name'] = $city_name;
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
                $db_lead_info['open_mode_final'] = $open_mode_final;
                $db_lead_info['open_state'] = $open_mode_final? '1' : '0';
                $db_lead_info['token'] = $token;
                $db_lead_info['request_id'] = $request_id;
                $db_lead_info['billed'] = $billed;
                $db_lead_info['duplicate_id'] = $duplicate_lead ? $duplicate_lead : "";
                $db_lead_info['send_state'] = '0';
                $db_lead_info['resource'] = 'form';
                
                
                $user_lead_id = SiteUser_leads::add_user_lead($db_lead_info,$user);

                $auth_link = get_config('main_website_url')."/myleads/leads/auth/?token=".$token;
                

                $email_info = array(
                    'lead'=>$email_lead_info,
                    'user'=>$user,
                    'site'=>$this->controller->data['site'],
                    'auth_link'=>$auth_link,
                    'lead_id'=>$user_lead_id
                );

                $email_content = $this->controller->include_ob_view('emails_send/user_lead_alert.php',$email_info);
                
                
                $user_send_times = Leads_complex::get_user_send_times($user_id);
                $email_pending_message = array(
                    'user_id'=>$user['info']['id'],
                    'email_to'=>$user['info']['email'],
                    'title'=>"בקשה להצעת מחיר באתר",
                    'content'=>$email_content,
                    'send_times'=>$user_send_times,
                    'lead_id'=>$user_lead_id
                );
                User_pending_emails::create($email_pending_message);
            }
        }
	}
?>