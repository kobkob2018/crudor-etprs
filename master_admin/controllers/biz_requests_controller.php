<?php
  class Biz_requestsController extends CrudController{
    public $add_models = array('biz_categories','masterBiz_requests','cities','MasterLeads_complex','masterUser_leads');

    protected $session_filter = false;

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
 
    protected function reset_filter(){
        session__unset('biz_requests_filter');
        return $this->redirect_to(inner_url('biz_requests/list/'));
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
            'limit_count'=>$this->get_request_filter_param('limit_count','0'),
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
            'limit_count'=>$filter_input['limit_count'],
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
                'label'=>'פייסבוק',
                'value'=>'1',
                'checked_str'=>'',
            ),
            '2'=>array(
                'label'=>'גוגל',
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

        $biz_requests = MasterBiz_requests::get_request_list($filter);
        
        $info = array(
            'filter'=>$filter,
            'filter_input'=>$filter_input,
            'status_options'=>$status_options,
            'referrer_options'=>$referrer_options,
            'biz_requests'=>$biz_requests,
            'campaign_types_checked'=>$filter_campaign_types_checked,
            'campaign_types_checkboxes'=>$campaign_types_checkboxes
        );
        $this->include_view('biz_requests/list.php',$info);
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
            $user_id_arr[] = $user_id;
        }
        
        
        $active_user_id_arr = MasterLeads_complex::filter_inactive_users($user_id_arr);

        $biz_request = MasterBiz_requests::get_request($_REQUEST['row_id']);

        $duplicated_user_leads = MasterLeads_complex::get_duplicated_user_leads($active_user_id_arr, $biz_request);

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
            $db_lead_info['send_state'] = '0';
            $db_lead_info['resource'] = 'form';

            $user_lead_id = MasterUser_leads::add_user_lead($db_lead_info,$user_id);

            $auth_link = get_config('master_url')."/myleads/leads/auth/?token=".$token;

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