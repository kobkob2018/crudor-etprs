<?php
  class Net_messagesController extends CrudController{
    public $add_models = array("net_messages","biz_categories");

    protected function init_setup($action){
        return parent::init_setup($action);
    }

    public function list(){
        $filter_arr = array();
        $net_messages = Net_messages::get_list($filter_arr, '*');
        $this->data['net_messages'] = $net_messages;
        $this->data['fields_collection'] = Net_messages::setup_field_collection();
        $this->include_view('net_messages/list.php');
    }

    public function send(){
        $this->add_model('net_message_cat');
        if(!isset($_REQUEST['row_id'])){
            return $this->eject_redirect();
        }
        $row_id = $_REQUEST['row_id'];
        $item_info = $this->get_item_info($row_id);
        $item_cats = $this->get_item_assign_list_for_cat($row_id);
        $user_list = net_message_cat::get_cat_users($item_cats);
        $user_count = 0;
        foreach($user_list as $user){
            $this->send_message_to_user($item_info,$user);
            $user_count++;

        }
        Net_messages::update_send_status($item_info['id'], $user_count);
        SystemMessages::add_success_message("ההודעה נשלחה ללקוחות");
        return $this->eject_redirect();

    }

    protected function send_message_to_user($message_info,$user){
        $email_to = $user['email'];
        $email_content = $message_info['msg'];
        $email_title = $message_info['title'];
        foreach($user as $param_key=>$param_val){
            $email_content = str_replace("{{$param_key}}",$param_val,$email_content);
            $email_title = str_replace("{{$param_key}}",$param_val,$email_title);
        }

        $this->send_email($email_to, $email_title, $email_content);

        Net_messages::add_user_read_message($user['id'], $message_info['id']);
    }

    public function select_cats(){
        $this->add_model('net_message_cat');
        $this->setup_tree_select_info(Net_message_cat::$tree_select_info);

        $this->include_view("net_messages/select_cats.php");
    }

    public function assign_to_item_for_cat($row_id,$selected_assigns){
        $this->add_model('net_message_cat');   
        Net_message_cat::assign_cats_to_item($row_id,$selected_assigns);
    }

    public function get_assign_item_offsprings_tree_for_cat($payload){
        $this->add_model('biz_categories');
        return Biz_categories::simple_get_item_offsprings_tree('0','id, label, parent',array(), $payload);
    }

    public function get_item_assign_list_for_cat($row_id){
        $this->add_model("net_message_cat");
        return Net_message_cat::get_item_cat_list($row_id);
    }

    public function add_assign_success_message_for_cat(){
        SystemMessages::add_success_message("הקטגוריות שוייכו בהצלחה");
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
        $this->include_view('net_messages/edit.php');
    }

    public function include_add_view(){
        $this->include_view('net_messages/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("הבאנר עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("הבאנר נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("הבאנר נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר באנר");
    }   

    protected function delete_item($row_id){
      return Net_messages::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Net_messages::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('net_messages/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("net_messages/edit/?row_id=".$item_info['id']);
    }

    public function delete_url($item_info){
        return inner_url("net_messages/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return Net_messages::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Net_messages::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        return Net_messages::create($fixed_values);
    }
  }
?>