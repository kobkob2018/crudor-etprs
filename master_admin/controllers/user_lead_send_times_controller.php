<?php

  class user_lead_send_timesController extends CrudController{
    public $add_models = array("user_lead_send_times", "users");

    protected function init_setup($action){
        $this->data['add_leads_menu'] = true;
        $user_id = $this->add_user_info_data();
        if(!$user_id){
            return $this->redirect_to(inner_url("users/list/"));
            return false;
        }

        return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $hours_info = user_lead_send_times::get_list($filter_arr,"id");      
        $this->data['hours_info'] = $hours_info;
        $user_id = $_GET['user_id'];
        if(empty($hours_info)){
            return $this->redirect_to(inner_url("user_lead_send_times/add/?user_id=$user_id"));
        }
        else{
            $row_id = $hours_info[0]['id'];
            return $this->redirect_to(inner_url("user_lead_send_times/edit/?user_id=$user_id&row_id=$row_id"));
        }
    }

    protected function add_user_info_data(){

        if(!isset($_GET['user_id'])){
            return false;
        }
        $user_id = $_GET['user_id'];
        $user_info = Users::get_by_id($user_id, 'id, full_name');
        $this->data['user_info'] = $user_info;
        if($user_info && isset($user_info['id'])){
            return $user_info['id'];
        }
    }

    protected function get_base_filter(){
        $user_id = $this->add_user_info_data();
        if(!$user_id){
            return;
        }

        $filter_arr = array(
            'user_id'=>$user_id,
    
        );  
        return $filter_arr;     
    }

    public function edit(){
        return parent::edit();
    }

    public function updateSend(){
        $row_id = $_REQUEST['row_id'];
        $fixed_values = array(
            'display'=>$_REQUEST['row']['display'],
            'time_groups'=>json_encode($_REQUEST['row']['time_groups'])
        );
        
        User_lead_send_times::update($row_id, $fixed_values);
        $this->update_success_message();
        return $this->redirect_to(inner_url('user_lead_send_times/edit/?user_id='.$this->data['user_info']['id']).'&row_id='.$row_id);
    }

    public function add(){
        return parent::add();
    }       

    public function createSend(){
        $user_id = $this->add_user_info_data();
        $fixed_values = array(
            'user_id'=>$user_id,
            'display'=>$_REQUEST['row']['display'],
            'time_groups'=>json_encode($_REQUEST['row']['time_groups'])
        );
        $row_id = User_lead_send_times::create($fixed_values);
        $this->update_success_message();
        return $this->redirect_to(inner_url('user_lead_send_times/edit/?user_id='.$this->data['user_info']['id']).'&row_id='.$row_id);
    }

    public function build_time_groups($field_key, $build_field){
        $hour_groups_json = $this->get_form_input($field_key);
        $hour_groups = json_decode($hour_groups_json, true);
        if(!$hour_groups){
            $hour_groups = array();
        }
        $options = array(
            'hours'=>array(),
            'minutes'=>array(),
            'days'=>array(
                '1'=>array('label'=>'ראשון','value'=>'1','default_checked'=>'checked'),
                '2'=>array('label'=>'שני','value'=>'2','default_checked'=>'checked'),
                '3'=>array('label'=>'שלישי','value'=>'3','default_checked'=>'checked'),
                '4'=>array('label'=>'רביעי','value'=>'4','default_checked'=>'checked'),
                '5'=>array('label'=>'חמישי','value'=>'5','default_checked'=>'checked'),
                '6'=>array('label'=>'שישי','value'=>'6','default_checked'=>''),
                '7'=>array('label'=>'שבת','value'=>'7','default_checked'=>'')
            )
        );
        for($i=0; $i<25; $i++){
            $value = str_pad($i,2,"0",STR_PAD_LEFT);
           // $value = number_format((int)$i, 2);
            $options['hours'][] = array('value'=>$value);
        }
        for($i=0; $i<60; $i++){
            $value = str_pad($i,2,"0",STR_PAD_LEFT);
            $options['minutes'][] = array('value'=>$value);
        }  

        $info_payload = array(
            'fields'=>array(
                $field_key=>array('hour_groups'=>$hour_groups)
            ),
            'options'=>$options
        );
        $this->include_view('form_builder/hours_select.php',$info_payload);
    } 

    public function include_edit_view(){
        $this->include_view('user_lead_send_times/edit.php');
    }

    public function include_add_view(){
        $this->include_view('user_lead_send_times/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("הזמנים עודכנו בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("הזמנים עודכנו בהצלחה");

    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר לקוח");
    }   

    protected function get_item_info($row_id){
      return User_lead_send_times::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('user_lead_send_times/list/?user_id='.$this->data['user_info']['id']);
    }

    protected function get_fields_collection(){
      return User_lead_send_times::setup_field_collection();
    }
    
  }
?>