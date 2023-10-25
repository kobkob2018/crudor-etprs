<?php
  class Leads_backupController extends CrudController{
    public $add_models = array('leads_backup');

    protected $session_filter = false;

    protected function init_filter_session(){
        $this->session_filter = array();
        if(isset($_REQUEST['filter'])){
            $this->session_filter = $_REQUEST['filter'];
            session__set("leads_backup_filter", $this->session_filter);
        }
        elseif(session__isset("leads_backup_filter")){
            $this->session_filter = session__get("leads_backup_filter");
        }
    }
    
    protected function reset_filter(){
        session__unset('leads_backup_filter');
        return $this->redirect_to(inner_url('leads_backup/list/'));
    }

    public function list(){
        
        if(isset($_REQUEST['reset_filter'])){
            return $this->reset_filter();
        }
        $this->init_filter_session();
        $filter_input = array(
            'page'=>$this->get_request_filter_param('page','1'),
            'page_limit'=>'100',
            'free'=>$this->get_request_filter_param('free'),
        );



        $filter = array(
            'page'=>$filter_input['page'],
            'page_limit'=>$filter_input['page_limit'],
            'free'=>$filter_input['free'],
        );
       
        $biz_requests_arr = Leads_backup::get_request_list($filter);
        $calls_list = Leads_backup::get_calls_list($filter);
        $contacts_list = Leads_backup::get_contacts_list($filter);
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
            'biz_requests'=>$biz_requests,
            'calls'=>$calls_list['calls'],
            'contacts'=>$contacts_list['contacts']
        );
        $this->include_view('leads_backup/list.php',$info);
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


  }
?>