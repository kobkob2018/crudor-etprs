<?php
  class Users_leadsController extends CrudController{
    public $add_models = array('biz_categories','masterUser_leads');

    protected $session_filter = false;

    protected function init_filter_session(){
        $this->session_filter = array();
        if(isset($_REQUEST['filter'])){
            $this->session_filter = $_REQUEST['filter'];
            session__set("users_leads_filter", $this->session_filter);
        }
        elseif(session__isset("users_leads_filter")){
            $this->session_filter = session__get("users_leads_filter");
        }
    }
 
    protected function reset_filter(){
        session__unset('users_leads_filter');
        return $this->redirect_to(inner_url('users_leads/list/'));
    }

    public function list(){
        
        if(isset($_REQUEST['reset_filter'])){
            return $this->reset_filter();
        }
        $this->init_filter_session();
        $filter_input = array(
            'page'=>$this->get_request_filter_param('page','1'),
            'page_limit'=>'10',
            'status'=>$this->get_request_filter_param('status','0'),
            'date_s'=>$this->get_request_filter_param('date_s', $this->get_default_date_s()),
            'date_e'=>$this->get_request_filter_param('date_e'),
            'free'=>$this->get_request_filter_param('free'),
            'filter_selected_users'=>$this->get_request_filter_param('filter_selected_users','1',true)
        );



        $filter = array(
            'page'=>$filter_input['page'],
            'page_limit'=>$filter_input['page_limit'],
            'status'=>$filter_input['status'],
            'date_s'=>$this->get_en_date($filter_input['date_s']),
            'date_e'=>$this->get_en_date($filter_input['date_e']),
            'free'=>$filter_input['free'],
            'filter_selected_users'=>$filter_input['filter_selected_users']
        );

        $filter_selected_users_checked = "";
        if($filter['filter_selected_users']){
            $filter_selected_users_checked = "checked";
        }

        $status_options = $this->status_options;

        if($filter['status'] == '' || !isset($status_options[$filter['status']])){
            $status_options['all']['selected_str'] = 'selected';
        }
        else{
            $status_options[$filter['status']]['selected_str'] = 'selected';
        }
       
        $users_leads_arr = masterUser_leads::get_users_lead_list($filter);
        $row_count = $users_leads_arr['row_count'];
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

        $users_leads = $users_leads_arr['users_leads'];

        $info = array(
            'filter'=>$filter,
            'filter_input'=>$filter_input,
            'status_options'=>$status_options,
            'page_options'=>$page_options,
            'users_leads'=>$users_leads,
            'filter_selected_users'=>$filter_selected_users_checked
        );
        $this->include_view('users_leads/list.php',$info);
    }

    public function bulk_update_status(){
        
        if(!isset($_REQUEST['bulk_lead'])){
            return $this->redirect_to(inner_url('users_leads/list/'));
        }
        if(empty($_REQUEST['bulk_lead'])){
            SystemMessages::add_err_message("לא נבחרו לידים");
            return $this->redirect_to(inner_url('users_leads/list/'));
        }
        $status = $_REQUEST['status'];
        $lead_id_arr = array();
        foreach($_REQUEST['bulk_lead'] as $lead_id=>$checked){
            $lead_id_arr[] = $lead_id;
        }
        
        MasterUser_leads::bulk_update_status($status,$lead_id_arr);
        SystemMessages::add_success_message("הסטטוסים עודכנו בהצלחה");
        return $this->redirect_to(inner_url('users_leads/list/'));
    }

    public function update_lead(){
        if(!isset($_REQUEST['row_id']) || !isset($_REQUEST['status'])){
            return $this->redirect_to(inner_url('users_leads/list/'));
        }

        $update_arr = array();
        $update_arr['status'] = $_REQUEST['status'];
        $update_arr['note'] = $_REQUEST['note'];
        $update_arr['mark'] = '0';
        if(isset($_REQUEST['mark'])){
            $update_arr['mark'] = '1';
        }
        MasterUser_leads::update($_REQUEST['row_id'],$update_arr);
        SystemMessages::add_success_message("הליד עודכן בהצלחה");
        return $this->redirect_to(inner_url('users_leads/list/'));
    }

    protected function get_request_filter_param($param_name,$default_val = "",$checkbox_default = false){
        if(isset($this->session_filter[$param_name])){
            return $this->session_filter[$param_name];
        }
        elseif($checkbox_default && isset($this->session_filter['checkboxes_set'])){
            return "0";
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
        return inner_url('users_leads/list/');
    }

    protected $status_options = array(
        'all'=>array(
            'label'=>'הכל',
            'value'=>'all',
            'selected_str'=>''
        ),
        '0'=>array(
            'label'=>'מתעניין חדש',
            'value'=>'0',
            'selected_str'=>''
        ),
        '1'=>array(
            'label'=>'נוצר קשר',
            'value'=>'1',
            'selected_str'=>''
        ),
        '2'=>array(
            'label'=>'סגירה עם לקוח',
            'value'=>'2',
            'selected_str'=>''
        ),
        '3'=>array(
            'label'=>'לקוחות רשומים',
            'value'=>'3',
            'selected_str'=>''
        ),
        '4'=>array(
            'label'=>'לא רלוונטי',
            'value'=>'4',
            'selected_str'=>''
        ),
        '5'=>array(
            'label'=>'מחכה לטלפון',
            'value'=>'5',
            'selected_str'=>''
        ),
    );

    public function json_to_arr($json_str = ""){
        if($json_str == ""){
            return array();
        }
        else{
            $arr = json_decode($json_str,true);
            if ($arr) {
                return $arr;
            }
            return array("more-info:"=>$json_str);
        }

    }
  }
?>