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

















    public function list_old(){
      $site_users = array();
      $roll_options = SiteUsers::get_admin_roll_options();
      $roll_options_indexed = Helper::eazy_index_arr_by('value',$roll_options,'title');
      foreach(SiteUsers::get_site_users_list($this->data['work_on_site']['id']) as $site_user){
          $user_name_arr = Users::get_by_id($site_user['user_id'],"full_name");
          $site_user['user_name'] = $user_name_arr['full_name'];
          $site_user['roll_title'] = $roll_options_indexed[$site_user['roll']];
          $site_users[] = $site_user;
      }
      $this->data['site_user_list'] = $site_users;
      $this->include_view('site_users_new/list.php');
    }

    public function view(){
        if(!isset($_GET['row_id'])){
            return $this->redirect_to(inner_url('siteUsers/list/'));
        }

        $site_user_info = SiteUsers::get_by_id($_GET['row_id']);
        if(!$site_user_info){
            return $this->redirect_to(inner_url('siteUsers/list/'));
        }
       
        $user_info = Users::get_by_id($site_user_info['user_id'],'full_name');
        $site_user_info['user_name'] = $user_info['full_name'];
        $this->data['site_user_info'] = $site_user_info;

        
        $form_handler = $this->init_form_handler();
        $form_handler->update_fields_collection(SiteUsers::setup_field_collection());
        $form_handler->setup_db_values($this->data['site_user_info']);

        $this->send_action_proceed();


        $this->include_view('site_users_new/view.php');
            
		}

    public function updateSend_old(){
      if(!isset($_REQUEST['row_id'])){
          return;
      }
      $row_id = $_REQUEST['row_id'];
      $form_handler = $this->init_form_handler();
      $validate_result = $form_handler->validate();
      if($validate_result['success']){
          SiteUsers::update($row_id,$validate_result['fixed_values']);
          SystemMessages::add_success_message("תפקיד המנהל עודכן");
          $this->redirect_to(inner_url('siteUsers/list/'));
      }
      else{
          if(!empty($validate_result['err_messages'])){
              $this->data['form_err_messages'] = $validate_result['err_messages'];
          }
      }
    }

    public function add_old(){
      $form_handler = $this->init_form_handler();
      $form_handler->update_fields_collection(SiteUsers::setup_field_collection());
      $this->send_action_proceed();
      $this->include_view('site_users_new/add.php');           
		}       

    public function delete_old(){
      if(!isset($_REQUEST['row_id'])){
          SystemMessages::add_err_message("לא נבחרה שורה");
          return $this->redirect_to(inner_url("siteUsers/list/"));
      }

      $row_id = $_REQUEST['row_id'];
      SiteUsers::delete($row_id);
      SystemMessages::add_success_message("המנהל הוסר בהצלחה");
      return $this->redirect_to(inner_url("siteUsers/list/"));         
		}  

    public function createSend_old(){
      $form_handler = $this->init_form_handler();
      $validate_result = $form_handler->validate();
      if($validate_result['success']){
          $validate_result['fixed_values']['site_id'] = $this->data['work_on_site']['id'];
          $row_id = SiteUsers::create($validate_result['fixed_values']);
          SystemMessages::add_success_message("המנהל נוסף בהצלחה");
          $this->redirect_to(inner_url("siteUsers/list/"));
      }
      else{
          if(!empty($validate_result['err_messages'])){
              $this->data['form_err_messages'] = $validate_result['err_messages'];
          }
      }
    }
  }
?>