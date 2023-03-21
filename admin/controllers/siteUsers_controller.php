<?php

  class SiteUsersController extends CrudController{
    public $add_models = array("siteUsers");

    protected function handle_access($action){
        //this module is for master_admin only!!!
        return $this->call_module('admin','handle_access_user_is','master_admin');
    }

    public function list(){
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
      $this->include_view('site_users/list.php');
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


        $this->include_view('site_users/view.php');
            
		}

    public function updateSend(){
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

    public function add(){
      $form_handler = $this->init_form_handler();
      $form_handler->update_fields_collection(SiteUsers::setup_field_collection());
      $this->send_action_proceed();
      $this->include_view('site_users/add.php');           
		}       

    public function delete(){
      if(!isset($_REQUEST['row_id'])){
          SystemMessages::add_err_message("לא נבחרה שורה");
          return $this->redirect_to(inner_url("siteUsers/list/"));
      }

      $row_id = $_REQUEST['row_id'];
      SiteUsers::delete($row_id);
      SystemMessages::add_success_message("המנהל הוסר בהצלחה");
      return $this->redirect_to(inner_url("siteUsers/list/"));         
		}  

    public function createSend(){
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

    public function site_user_validate_by($value){
      $return_array =  array(
          'success'=>true
      );
      if($value == 'notgood@gmail.com'){
          $return_array['success'] = false;
          $return_array['message'] = "האימייל שבחרת לא מותר";
      }
      return $return_array;
    }



  }
?>