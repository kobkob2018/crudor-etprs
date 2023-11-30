<?php
  class Portal_userController extends CrudController{
    public $add_models = array("portal_user");

    protected function init_setup($action){
        $user_id = $this->add_user_info_data();
        if(!$user_id){
            exit("here");
            return $this->redirect_to(inner_url("site_users/list/"));
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
        $settings_info = Portal_user::find($filter_arr,"id");      
        
        if($settings_info){
            $row_id = $settings_info['id'];
            return $this->redirect_to(inner_url("portal_user/edit/?user_id=$user_id&row_id=$row_id"));
        }
        else{
            return $this->redirect_to(inner_url("portal_user/add/?user_id=$user_id"));
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
        $this->include_view('portal_user/edit.php');
    }

    public function include_add_view(){
        $this->include_view('portal_user/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("הפורטל עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("הפורטל נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("פרטי הפורטל נמחקו");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר פורטל");
    }   

    protected function delete_item($row_id){
        return Portal_user::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Portal_user::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('portal_user/list/');
    }

    public function url_back_to_item($item_info){
        $row_id = $item_info['id'];
        $user_id = $_GET['user_id'];
        return inner_url("portal_user/edit/?user_id=$user_id&row_id=$row_id");
    }

    protected function after_delete_redirect(){
        $user_id = $_GET['user_id'];
        return $this->redirect_to(inner_url("/portal_user/list/?user_id=$user_id"));
    }

    public function delete_url($item_info){
        $row_id = $item_info['id'];
        $user_id = $item_info['user_id'];
        return inner_url("portal_user/delete/?user_id=$user_id&row_id=$row_id");
    }

    protected function get_fields_collection(){
        return Portal_user::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
        return Portal_user::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        if(!isset($_GET['user_id'])){
            SystemMessages::add_err_message($this->row_error_message());
            return $this->redirect_to("site_users/list/");
        }
        $user_id = $_GET['user_id'];
        $fixed_values['user_id'] = $user_id;
        return Portal_user::create($fixed_values);
    }

  }
?>