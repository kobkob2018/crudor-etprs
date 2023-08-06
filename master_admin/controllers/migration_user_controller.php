<?php
  class Migration_userController extends CrudController{
    public $add_models = array("sites","migration_user");

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $migration_user = Migration_user::get_list($filter_arr,"id, user_id");      
        $this->data['migration_user'] = $migration_user;
        if(empty($migration_user)){
            return $this->redirect_to(inner_url("migration_user/add/?user_id=".$_REQUEST['row_id']));
        }
        else{
            $migration_user_id = $migration_user[0]['user_id'];
            $row_id = $migration_user[0]['id'];
            return $this->redirect_to(inner_url("migration_user/edit/?user_id=$migration_user_id&row_id=$row_id"));
        }
    }

    protected function get_base_filter(){
        $user_id = $_REQUEST['row_id'];
        $filter_arr = array(
            'user_id'=>$user_id
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
        $this->include_view('migration_user/edit.php');
    }

    public function include_add_view(){
        $this->include_view('migration_user/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("התיאום עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("התיאום נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("התיאום נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר תיאום");
    }   

    protected function delete_item($row_id){
      return Migration_user::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Migration_user::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('migration_user/list/?row_id='.$_REQUEST['user_id']);
    }

    public function url_back_to_item($item_info){
      return inner_url("migration_user/edit/?user_id=".$_REQUEST['user_id']."&row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return Migration_user::setup_field_collection();
    }

    protected function update_item($item_id,$fixed_values){


        $old_user = $fixed_values['old_user'];
        $old_user_data = Migration_user::get_old_user_data_by_id($old_user);
        if(!$old_user_data){
            SystemMessages::add_err_message("לא נמצא המשתמש שציינת במערכת הישנה");
            $this->eject_redirect();
            return false;
        }
        $update_values['old_unk'] = $old_user_data['unk'];
        $update_values['old_id'] = $old_user_data['user_id'];
        $update_values['old_name'] = $old_user_data['name'];
        $update_values['old_full_name'] = $old_user_data['full_name'];
        return Migration_user::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        $user_id = $_REQUEST['user_id'];


        $update_values['user_id'] = $user_id;
        $old_user = $fixed_values['old_user'];
        $old_user_data = Migration_user::get_old_user_data_by_id($old_user);
        print_r_help($old_user_data);
        if(!$old_user_data){
            SystemMessages::add_err_message("לא נמצא המשתמש שציינת במערכת הישנה");
            $this->eject_redirect();
            return false;
        }
        $update_values['old_unk'] = $old_user_data['unk'];
        $update_values['old_id'] = $old_user_data['user_id'];
        $update_values['old_name'] = $old_user_data['name'];
        $update_values['old_full_name'] = $old_user_data['full_name'];
        return Migration_user::create($update_values);
    }

  }
?>