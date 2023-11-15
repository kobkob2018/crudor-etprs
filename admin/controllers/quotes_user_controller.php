<?php
  class Quotes_userController extends CrudController{
    public $add_models = array("quotes_user");

    protected function init_setup($action){
        $user_id = $this->add_user_info_data();
        if(!$user_id){
            exit("here");
            return $this->redirect_to(inner_url("quote_cats/list/"));
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
            SystemMessages::add_err_message("לא נבחר עורך הצעות מחיר");
            return $this->redirect_to("quote_cats/list/");
        }
        $user_id = $_GET['user_id'];
        //if(session__isset())
        $filter_arr = array('user_id'=>$user_id);
        $settings_info = Quotes_user::find($filter_arr,"id");      
        
        if($settings_info){
            $row_id = $settings_info['id'];
            return $this->redirect_to(inner_url("quotes_user/edit/?user_id=$user_id&row_id=$row_id"));
        }
        else{
            return $this->redirect_to(inner_url("quotes_user/add/?user_id=$user_id"));
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
        $this->include_view('quotes/user_edit.php');
    }

    public function include_add_view(){
        $this->include_view('quotes/user_add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("העורך עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("העורך נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("פרטי העורך נמחקו");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר עורך הצעת מחיר");
    }   

    protected function delete_item($row_id){
        Quotes_user::delete($row_id);
        return $this->set_user_quotes_statuses();
    }

    protected function get_item_info($row_id){
      return Quotes_user::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('quotes_user/list/');
    }

    public function url_back_to_item($item_info){
        $row_id = $item_info['id'];
        $user_id = $_GET['user_id'];
        return inner_url("quotes_user/edit/?user_id=$user_id&row_id=$row_id");
    }

    protected function after_delete_redirect(){
        $user_id = $_GET['user_id'];
        return $this->redirect_to(inner_url("/quotes_user/list/?user_id=$user_id"));
    }

    public function delete_url($item_info){
        $row_id = $item_info['id'];
        $user_id = $item_info['user_id'];
        return inner_url("quotes_user/delete/?user_id=$user_id&row_id=$row_id");
    }

    protected function get_fields_collection(){
        return Quotes_user::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
        $quotes_user_update = Quotes_user::update($item_id,$update_values);
        $this->set_user_quotes_statuses();
        return $quotes_user_update;
    }

    protected function create_item($fixed_values){
        if(!isset($_GET['user_id'])){
            SystemMessages::add_err_message($this->row_error_message());
            return $this->redirect_to("quote_cats/list/");
        }
        $user_id = $_GET['user_id'];
        $fixed_values['user_id'] = $user_id;
        $item_id = Quotes_user::create($fixed_values);
        $this->set_user_quotes_statuses();
        return $item_id;
    }

    protected function set_user_quotes_statuses(){
        if(!isset($_GET['user_id'])){
            return;
        }
        $user_id = $_GET['user_id'];
        $set_to_status = '9';
        $quotes_user = Quotes_user::find(array('user_id'=>$user_id));
        if($quotes_user){
            if($quotes_user['status'] == '1'){
                $set_to_status = '1'; 
            }
        }
        if($set_to_status == '9'){
            SystemMessages::add_success_message("הצעות המחיר של העורך עברו למצב השהייה");
        }
        else{
            SystemMessages::add_success_message("הצעות המחיר של העורך עברו למצב פעיל");
        }
        return Quotes_user::set_user_quotes_statuses($user_id,$set_to_status);
    }

  }
?>