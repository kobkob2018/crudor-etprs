<?php
  class Login_traceController extends CrudController{
    public $add_models = array("masterLogin_trace");

    protected function init_setup($action){
        return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();

        $list_info = $this->get_paginated_list_info($filter_arr,array('page_limit'=>'300'));
        
        $login_trace = MasterLogin_trace::add_users_details($list_info['list']);

        $this->data['login_trace'] = $login_trace;
        $this->include_view('login_trace/list.php',array('list'=>$login_trace,'filter_form'=>$list_info['filter_form']));

    }

    protected function get_paginated_list($filter_arr, $payload){
        $payload['order_by'] = $this->session_order_by.", id desc";
        return MasterLogin_trace::get_list($filter_arr, '*',$payload);
    }

    protected function get_filter_fields_collection(){
        return array();
    }

    protected function get_base_filter(){
    
        $filter_arr = array( );  
        return $filter_arr;     
    }

    protected function get_fields_collection(){
      return MasterLogin_trace::setup_field_collection();
    }

  }
?>