<?php

  class Site_usersController extends CrudController{


    public $add_models = array("users","site_users");

    protected function handle_access($action){
        switch ($action){
            case 'master_admin_add_me':
                return $this->call_module('admin','handle_access_user_is','master_admin');
                break;
            default:
                //this module is for master_admin only!!!
                return $this->call_module('admin','handle_access_site_user_is','master_admin');
                break;               
        }

    }


    protected function init_setup($action){
        return parent::init_setup($action);
    }  

    public function list(){
        //if(session__isset())
        
        $filter_arr = $this->get_base_filter();
        $fields_collection = Site_users::setup_field_collection();
        $site_users_list = Site_users::get_list($filter_arr,"*");      
        $this->data['site_users_list'] = $site_users_list;
        $this->include_view('site_users/list.php',array('fields_colection'=>$fields_collection));
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
        $user_id = $this->data['item_info']['user_id'];
        $user = Users::get_by_id($user_id,"full_name");
        $this->data['item_info']['full_name'] = $user['full_name'];
        $this->data['site_user_info'] = $this->data['item_info'];
        $this->include_view('site_users/edit.php');
    }


    public function master_admin_add_me(){
        
        //just for safety.. this one is already handled at the access handler
        if(!Helper::user_is('master_admin',$this->user)){
            return;
        }

        $site_id = $_REQUEST['site_id'];
        //check if user allready assigned to this site
        $site_user = Site_users::find(array('user_id'=>$this->user['id'],'site_id'=>$site_id));
        if($site_user){
            SystemMessages::add_err_message("כבר קיים שיוך שלך לאתר זה.");
            return $this->redirect_to(inner_url("userSites/list/"));
        }
        Site_users::create(array('user_id'=>$this->user['id'],'site_id'=>$site_id));
        SystemMessages::add_success_message("נוספת בהצלחה כמנהל ראשי לאתר זה");
        return $this->redirect_to(inner_url("userSites/list/"));
    }

    public function include_add_view(){
        $this->include_view('site_users/add.php');
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
      return inner_url('site_users/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("site_users/edit/?row_id=".$item_info['id']);
    }

    protected function after_delete_redirect(){
        return $this->redirect_to(inner_url("/site_users/list/"));
    }

    public function delete_url($item_info){
        return inner_url("site_users/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
        return Site_users::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
        $user_can_options = array();
        if(isset($update_values['user_can'])){
            $user_can_options = $update_values['user_can'];
            unset($update_values['user_can']);
        }
        Site_users::update_user_can($item_id,$update_values['user_id'],$this->data['work_on_site']['id'],$user_can_options);
        return Site_users::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        $user_can_options = array();
        if(isset($update_values['user_can'])){
            $user_can_options = $update_values['user_can'];
            unset($update_values['user_can']);
        }
        $fixed_values['site_id'] = $this->data['work_on_site']['id'];
        $item_id = Site_users::create($fixed_values);

        Site_users::update_user_can($item_id,$fixed_values['user_id'],$this->data['work_on_site']['id'],$user_can_options);
        
        return $item_id;
    }

    public function build_user_can_options(){
        $site_user_id = $this->get_form_input('user_id');
        $user_can_permissions = Site_users::get_user_can($this->data['work_on_site']['id'],$site_user_id);
        $user_can_options = Site_users::$user_can_options;
        foreach($user_can_options as $key=>$option){
            $checked = "";
            if(isset($user_can_permissions[$option['value']])){
                $checked = " checked ";
            }
            $user_can_options[$key]['checked'] = $checked;

        }
 

        $info_payload = array(
            'field_key'=>'user_can',
            'options'=>$user_can_options
        );
        $this->include_view('form_builder/checklist.php',$info_payload);
    } 




  }
?>