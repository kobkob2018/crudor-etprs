<?php
  class Biz_requestsController extends CrudController{
    public $add_models = array('biz_categories','masterBiz_requests','cities','masterLeads_complex','masterUser_leads');

    protected $session_filter = false;

    protected $city_names = array();

    protected function init_filter_session(){
        $this->session_filter = array();
        if(isset($_REQUEST['filter'])){
            $this->session_filter = $_REQUEST['filter'];
            session__set("biz_requests_filter", $this->session_filter);
        }
        elseif(session__isset("biz_requests_filter")){
            $this->session_filter = session__get("biz_requests_filter");
        }
    }

    protected function init_spam_filter_session(){
        $this->session_filter = array();
        if(isset($_REQUEST['filter'])){
            $this->session_filter = $_REQUEST['filter'];
            session__set("biz_requests_spam_filter", $this->session_filter);
        }
        elseif(session__isset("biz_requests_spam_filter")){
            $this->session_filter = session__get("biz_requests_spam_filter");
        }
    }
    
    protected function reset_filter(){
        session__unset('biz_requests_filter');
        return $this->redirect_to(inner_url('biz_requests/list/'));
    }

    protected function reset_spam_filter(){
        session__unset('biz_requests_spam_filter');
        return $this->redirect_to(inner_url('biz_requests/spam_list/'));
    }

    public function status_update(){
        if(!isset($_REQUEST['row_id']) || !isset($_REQUEST['status'])){
            return $this->redirect_to(inner_url('biz_requests/list/'));
        }
        $update_arr = array('status'=>$_REQUEST['status']);
        MasterBiz_requests::update($_REQUEST['row_id'],$update_arr);
        SystemMessages::add_success_message("הסטטוס עודכן בהצלחה");
        return $this->redirect_to(inner_url('biz_requests/list/'));
    }

    public function list(){
        
        if(isset($_REQUEST['reset_filter'])){
            return $this->reset_filter();
        }
        $this->init_filter_session();
        $filter_input = array(
            'page'=>$this->get_request_filter_param('page','1'),
            'page_limit'=>'100',
            'status'=>$this->get_request_filter_param('status','0'),
            'date_s'=>$this->get_request_filter_param('date_s', $this->get_default_date_s()),
            'date_e'=>$this->get_request_filter_param('date_e'),
            'referrer'=>$this->get_request_filter_param('referrer'),
            'ip'=>$this->get_request_filter_param('ip'),
            'free'=>$this->get_request_filter_param('free'),
            'filter_campaign_types'=>$this->get_request_filter_param('filter_campaign_types','0'),
            'campaign_types'=>$this->get_request_filter_param('campaign_types',array()),
        );



        $filter = array(
            'page'=>$filter_input['page'],
            'page_limit'=>$filter_input['page_limit'],
            'status'=>$filter_input['status'],
            'date_s'=>$this->get_en_date($filter_input['date_s']),
            'date_e'=>$this->get_en_date($filter_input['date_e']),
            'referrer'=>$filter_input['referrer'],
            'ip'=>$filter_input['ip'],
            'free'=>$filter_input['free'],
            'filter_campaign_types'=>$filter_input['filter_campaign_types'],
            'campaign_types'=>$filter_input['campaign_types'],
        );

        $filter_campaign_types_checked = "";
        if($filter['filter_campaign_types']){
            $filter_campaign_types_checked = "checked";
        }

        $campaign_types_checkboxes = array(
            '0'=>array(
                'label'=>'ללא',
                'value'=>'0',
                'checked_str'=>'',
            ),
            '1'=>array(
                'label'=>'גוגל',
                'value'=>'1',
                'checked_str'=>'',
            ),
            '2'=>array(
                'label'=>'פייסבוק',
                'value'=>'2',
                'checked_str'=>'',
            ),            
        );

        foreach($filter['campaign_types'] as $campaign_key=>$checked){
            $campaign_types_checkboxes[$campaign_key]['checked_str'] = "checked";
        }

        $status_options = $this->status_options;

        if($filter['status'] == '' || !isset($status_options[$filter['status']])){
            $status_options['all']['selected_str'] = 'selected';
        }
        else{
            $status_options[$filter['status']]['selected_str'] = 'selected';
        }

        $referrer_options_arr = MasterBiz_requests::get_referrer_options($filter);
        $referrer_options = array();
        foreach($referrer_options_arr as $site_ref){
            $referrer_options[$site_ref] = array(
                'label'=>$site_ref,
                'value'=>$site_ref,
                'selected_str'=>''
            );
        }

        if($filter['referrer'] != ''){
            if(isset($referrer_options[$filter['referrer']])){
                $referrer_options[$filter['referrer']]['selected_str'] = 'selected';
            }
        }
       
        $biz_requests_arr = MasterBiz_requests::get_request_list($filter);
        $row_count = $biz_requests_arr['row_count'];
        $next_page = true;
        $page_limit = intval($filter['page_limit']);
        $page_i = 1;
        $page_options = array();
        $page = intval($filter['page']);
        while($next_page){
            $page_option = array(
                'index'=>$page_i,
                'selected_str'=>'',
            );

            if($page_i == $page){
                $page_option['selected_str'] = ' selected ';
            }
            $page_options[] = $page_option;
            $limit_count = $page_i*$page_limit;
            if($limit_count < $row_count){
                $page_i++;
            }
            else{
                $next_page = false;
            }
        }

        //print_r_help($page_options);

        $biz_requests = $biz_requests_arr['biz_requests'];

        $info = array(
            'filter'=>$filter,
            'filter_input'=>$filter_input,
            'status_options'=>$status_options,
            'referrer_options'=>$referrer_options,
            'page_options'=>$page_options,
            'biz_requests'=>$biz_requests,
            'campaign_types_checked'=>$filter_campaign_types_checked,
            'campaign_types_checkboxes'=>$campaign_types_checkboxes
        );
        $this->include_view('biz_requests/list.php',$info);
    }


    public function spam_list(){
        $this->add_model("masterBiz_requests_spam");
        if(isset($_REQUEST['reset_spam_filter'])){
            return $this->reset_spam_filter();
        }
        $this->init_spam_filter_session();
        $filter_input = array(
            'page'=>$this->get_request_filter_param('page','1'),
            'page_limit'=>'100',
            'date_s'=>$this->get_request_filter_param('date_s', $this->get_default_date_s()),
            'date_e'=>$this->get_request_filter_param('date_e'),
            'ip'=>$this->get_request_filter_param('ip'),
            'free'=>$this->get_request_filter_param('free'),
        );



        $filter = array(
            'page'=>$filter_input['page'],
            'page_limit'=>$filter_input['page_limit'],
            'date_s'=>$this->get_en_date($filter_input['date_s']),
            'date_e'=>$this->get_en_date($filter_input['date_e']),
            'ip'=>$filter_input['ip'],
            'free'=>$filter_input['free']
        );

        $biz_requests_arr = MasterBiz_requests_spam::get_request_list($filter);
        $row_count = $biz_requests_arr['row_count'];
        $next_page = true;
        $page_limit = intval($filter['page_limit']);
        $page_i = 1;
        $page_options = array();
        $page = intval($filter['page']);
        while($next_page){
            $page_option = array(
                'index'=>$page_i,
                'selected_str'=>'',
            );

            if($page_i == $page){
                $page_option['selected_str'] = ' selected ';
            }
            $page_options[] = $page_option;
            $limit_count = $page_i*$page_limit;
            if($limit_count < $row_count){
                $page_i++;
            }
            else{
                $next_page = false;
            }
        }

        //print_r_help($page_options);

        $biz_requests = $biz_requests_arr['biz_requests'];

        $info = array(
            'filter'=>$filter,
            'filter_input'=>$filter_input,
            'page_options'=>$page_options,
            'biz_requests'=>$biz_requests
        );
        $this->include_view('biz_requests/spam_list.php',$info);
    }    


    public function get_city_name($city_id){
        if(!isset($this->city_names[$city_id])){
            $city_name = $city_id;
            $city = Cities::get_by_id($city_id);
            if($city){
                $city_name = $city['label'];
            }
            $this->city_names[$city_id] = $city_name;
        }

        return $this->city_names[$city_id];
    }

    public function edit(){
        return parent::edit();
    }

    public function include_edit_view(){
        $this->include_view('biz_requests/edit.php');
    }

    protected function update_success_message(){
        SystemMessages::add_success_message("הבקשה להצעת מחיר עודכנה בהצלחה");
        SystemMessages::add_err_message("יש לשים לב שלא עודכנו הלידים עצמם");

    }

    protected function get_item_info($row_id){
        return MasterBiz_requests::get_by_id($row_id);
    }

    public function url_back_to_item($item_info){
        return inner_url("biz_requests/view/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
        return MasterBiz_requests::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
        return MasterBiz_requests::update($item_id,$update_values);
    }

    public function view(){
        
        if(!isset($_REQUEST['row_id'])){
            return $this->redirect_to(inner_url('biz_requests/list/'));
        }
        $biz_request = MasterBiz_requests::get_request($_REQUEST['row_id']);
        $status_options = $this->status_options;
        
        $users_fit = MasterLeads_complex::find_users_for_lead($biz_request);
        $info = array(
            'biz_request'=>$biz_request,
            'status_options'=>$status_options,
            'users_fit'=>$users_fit
        );

        return $this->include_view('biz_requests/view.php',$info);
    }

    public function send_lead_to_users(){
        $this->set_layout("blank");
        
        if(!isset($_REQUEST['row_id'])){
            SystemMessages::add_err_message("אירעה שגיאה, אנא נסה שוב");
            return $this->eject_redirect();
        }
        if(!isset($_REQUEST['send_to_users']) || empty($_REQUEST['send_to_users'])){
            SystemMessages::add_err_message("לא נבחרו נותני שירות לשליחה");
            return $this->redirect_to(inner_url('biz_requests/view/?row_id='.$_REQUEST['row_id']));
        }
        $user_id_arr = array();
        foreach($_REQUEST['send_to_users'] as $user_id=>$checked){
            //make sure the send button was not clicked twice or more..
            $session_check = "request_".$_REQUEST['row_id']."_sent_to_".$user_id;
            if(session__isset($session_check)){
                continue;
            }
            session__set($session_check,"1");
            $user_id_arr[] = $user_id;
        }
        
        
        $active_user_id_arr = MasterLeads_complex::filter_inactive_users($user_id_arr);

        $biz_request = MasterBiz_requests::get_request($_REQUEST['row_id']);
        
        $duplicate_user_leads = MasterLeads_complex::get_duplicated_user_leads($active_user_id_arr, $biz_request);

        foreach($user_id_arr as $user_id){
            $user_info = MasterLeads_complex::get_user_info($user_id);
            $biz_name = $user_info['user']['biz_name'];
            if(!in_array($user_id,$active_user_id_arr)){
                SystemMessages::add_err_message($biz_name." - "." לא ניתן לשלוח ליד למשתמש לא פעיל ");
                continue;
            }

            $duplicate_lead = false;

            if(isset($duplicate_user_leads[$user_id])){
                $duplicate_lead = $duplicate_user_leads[$user_id];
            }

            $user_lead_settings = $user_info['lead_settings'];
            $token = md5(time().$biz_request['phone']);

            $biz_request['cat_tree_name'] = $this->get_cat_tree_name($biz_request['cat_id']);

            $db_lead_info = $biz_request;

            $email_lead_info = $biz_request;

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
            $db_lead_info['request_id'] = $biz_request['id'];
            $db_lead_info['billed'] = $billed;
            $db_lead_info['duplicate_id'] = $duplicate_lead ? $duplicate_lead : "";
            $db_lead_info['send_state'] = '1'; //sending immidiatly not pending
            $db_lead_info['resource'] = 'form';

            $user_lead_id = MasterUser_leads::add_user_lead($db_lead_info,$user_id);
            if($user_lead_id){
                MasterBiz_requests::add_1_reciver($_REQUEST['row_id']);
            }
            $auth_link = get_config('master_url')."/myleads/leads/auth/?token=".$token."&lead=".$user_lead_id."&user=".$user_id;

            $requst_site = Sites::get_by_id($biz_request['site_id']);

            $email_info = array(
                'lead'=>$email_lead_info,
                'user'=>$user_info['user'],
                'site'=>$requst_site,
                'auth_link'=>$auth_link,
                'lead_id'=>$user_lead_id
            );

            $email_content = $this->include_ob_view('emails_send/masterUser_lead_alert.php',$email_info);

            $email_title = "בקשה להצעת מחיר באתר";

            $this->send_email($user_info['user']['email'],$email_title,$email_content);
            $duplicate_str = "";
            if($duplicate_lead){
                $duplicate_str = " [ליד כפול, לא חוייב] ";
            }
            SystemMessages::add_success_message($biz_name." - "." הליד נשלח בהצלחה ".$duplicate_str);
        }
        return $this->redirect_to(inner_url('biz_requests/view/?row_id='.$_REQUEST['row_id']));
    }

    protected function get_cat_tree_name($cat_id){
        $cat_tree = Biz_categories::simple_get_item_parents_tree($cat_id,"*");
       
        

        $cat_tree_name_arr = array();
        foreach($cat_tree as $cat){
            $cat_tree_name_arr[] = $cat['label']; 
        }
        $cat_tree_name = implode(", ",$cat_tree_name_arr);
        return $cat_tree_name;
    }

    protected function get_request_filter_param($param_name,$default_val = ""){
        if(isset($this->session_filter[$param_name])){
            return $this->session_filter[$param_name];
        }
        return $default_val;
    }

    protected function get_en_date($heb_date){
        if(!$heb_date){
            return "";
        }
        $date = DateTime::createFromFormat("d-m-Y",$heb_date);
        return $date->format('Y-m-d');
    }

    protected function get_default_date_s(){
        return date('d-m-Y', strtotime('-30 days'));
    }

    public function eject_url(){
        return inner_url('biz_requests/list/');
    }

    protected $status_options = array(
        'all'=>array(
            'label'=>'הכל',
            'value'=>'all',
            'selected_str'=>''
        ),
        '0'=>array(
            'label'=>'הצעות חדשות',
            'value'=>'0',
            'selected_str'=>''
        ),
        '1'=>array(
            'label'=>'הצעות בטיפול',
            'value'=>'1',
            'selected_str'=>''
        ),
        '2'=>array(
            'label'=>'הצעות שמחכות',
            'value'=>'2',
            'selected_str'=>''
        ),
        '3'=>array(
            'label'=>'הצעות שנשלחו',
            'value'=>'3',
            'selected_str'=>''
        ),
        '4'=>array(
            'label'=>'הצעות לא רלוונטיות',
            'value'=>'4',
            'selected_str'=>''
        ),
        '5'=>array(
            'label'=>'הצעות מחוקות',
            'value'=>'5',
            'selected_str'=>''
        ),
    );
  }
?>