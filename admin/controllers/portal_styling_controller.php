<?php
  class Portal_stylingController extends CrudController{
    public $add_models = array("portal_styling");

    protected function handle_access($action){   
      return $this->call_module('admin','handle_access_site_user_is','master_admin');
    }
    
    protected function init_setup($action){
        $user_id = $this->add_user_info_data();
        if(!$user_id){
            SystemMessages::add_err_message("לא נבחר משתמש");
            $this->redirect_to(inner_url("site_users/list/"));
            return false;
        }

        return parent::init_setup($action);
    }

    protected function add_user_info_data(){

        if(!isset($_GET['user_id'])){
            return false;
        }
        $user_id = $_GET['user_id'];
        $user_info = Users::get_by_id($user_id, 'id, full_name, biz_name');
        $this->data['user_info'] = $user_info;
        if($user_info && isset($user_info['id'])){
            return $user_info['id'];
        }
    }

    public function list(){
        if(!isset($_GET['user_id'])){
            SystemMessages::add_err_message("לא נבחר משתמש פורטל");
            return $this->redirect_to("site_users/list/");
        }
        $user_id = $_GET['user_id'];
        //if(session__isset())
        $filter_arr = array('user_id'=>$user_id);
        $settings_info = Portal_styling::find($filter_arr,"id");      
        
        if($settings_info){
            $row_id = $settings_info['id'];
            return $this->redirect_to(inner_url("portal_styling/edit/?user_id=$user_id&row_id=$row_id"));
        }
        else{
            return $this->redirect_to(inner_url("portal_styling/add/?user_id=$user_id"));
        }
    }

    public function edit(){
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
        $this->include_view('portal_styling/edit.php');
    }

    public function include_add_view(){
        $this->include_view('portal_styling/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("העיצוב עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("העיצוב נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("העיצוב נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר עיצוב");
    }   

    protected function delete_item($row_id){
      return Portal_styling::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Portal_styling::get_by_id($row_id);
    }

    public function eject_url(){
        if(!isset($this->data['user_info'])){
            return inner_url('site_users/list/');
        }
        return inner_url('portal_styling/list/?user_id='.$this->data['user_info']['id']);
    }

    public function url_back_to_item($item_info){
        $row_id = $item_info['id'];
        $user_id = $_GET['user_id'];
        return inner_url("portal_styling/edit/?user_id=$user_id&row_id=$row_id");
    }

    protected function get_fields_collection(){
      return Portal_styling::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
        return Portal_styling::update($item_id,$update_values);
    }

    protected function get_priority_space($filter_arr, $item_to_id){
        return Portal_styling::get_priority_space($filter_arr, $item_to_id);
    }

    protected function create_item($fixed_values){
        $user_id = $this->data['user_info']['id'];
        $fixed_values['user_id'] = $user_id;
        return Portal_styling::create($fixed_values);
    }

  }
?>