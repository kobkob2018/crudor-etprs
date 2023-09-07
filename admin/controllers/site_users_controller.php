<?php

  class Site_usersController extends CrudController{


    public $add_models = array("site_users");

    protected function handle_access($action){
        //this module is for master_admin only!!!
        return $this->call_module('admin','handle_access_user_is','master_admin');
    }


    protected function init_setup($action){
        return parent::init_setup($action);
    }  

    public function list(){
        //if(session__isset())
        
        $filter_arr = $this->get_base_filter();
        $site_users_list = Site_users::get_list($filter_arr,"*");      
        $this->data['site_users_list'] = $site_users_list;
        $this->include_view('site_users_new/list.php');
    }

    protected function get_base_filter(){
        $filter_arr = array(
            'site_id'=>$this->data['work_on_site']['id']
        );  
        return $filter_arr;      
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
        $this->data['site_user_info'] = $this->data['item_info'];
        $this->include_view('site_users_new/edit.php');
    }

    public function include_add_view(){
        $this->include_view('site_users_new/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("המנהל עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("המנהל נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("המנהל נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר מנהל");
    }   

    protected function delete_item($row_id){
      return Site_users::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Site_users::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('site_users_new/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("site_users_new/edit/?row_id=".$item_info['id']);
    }

    protected function after_delete_redirect(){
        return $this->redirect_to(inner_url("/site_users_new/list/"));
    }

    public function delete_url($item_info){
        return inner_url("site_users_new/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
        return Site_users::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Site_users::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        $fixed_values['site_id'] = $this->data['work_on_site']['id'];
        return Site_users::create($fixed_values);
    }








  }
?>