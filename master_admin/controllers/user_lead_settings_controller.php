<?php

  class User_lead_settingsController extends CrudController{
    public $add_models = array("user_lead_settings", "users");

    protected function init_setup($action){
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
        $settings_info = User_lead_settings::get_list($filter_arr,"id");      
        $user_id = $_GET['user_id'];
        if(empty($settings_info)){
            return $this->redirect_to(inner_url("user_lead_settings/add/?user_id=$user_id"));
        }
        else{
            $row_id = $settings_info[0]['id'];
            return $this->redirect_to(inner_url("user_lead_settings/edit/?user_id=$user_id&row_id=$row_id"));
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
        $this->data['add_leads_menu'] = true;
        return parent::edit();
    }

    public function updateSend(){
        return parent::updateSend();
    }

    public function add(){
        return parent::add();
    }       

    public function createSend(){
        return parent::createSend();
    }

    public function delete(){
        return parent::delete();      
    }

    public function include_edit_view(){
        $this->include_view('user_lead_settings/edit.php');
    }

    public function include_add_view(){
        $this->include_view('user_lead_settings/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("האפיון עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("האפיון נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("האפיון נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר אפיון");
    }   

    protected function delete_item($row_id){
      return User_lead_settings::delete($row_id);
    }

    protected function get_item_info($row_id){
      return User_lead_settings::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('user_lead_settings/list/?user_id='.$this->data['user_info']['id']);
    }

    public function url_back_to_item($item_info){
      return inner_url("user_lead_settings/edit/?user_id=".$this->data['user_info']['id']."&row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return User_lead_settings::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
        $this->add_model("user_lead_rotation");
        $this->add_model("user_lead_visability");
        
        $user_id = $this->data['user_info']['id'];
        User_lead_rotation::create_for_user($user_id);
        User_lead_visability::create_for_user($user_id);    
        return User_lead_settings::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        $this->add_model("user_lead_rotation");
        $this->add_model("user_lead_visability");

        $user_id = $this->data['user_info']['id'];
        User_lead_rotation::create_for_user($user_id);
        User_lead_visability::create_for_user($user_id); 
        
        $fixed_values['user_id'] = $user_id;
        return User_lead_settings::create($fixed_values);
        
    }
    
  }
?>