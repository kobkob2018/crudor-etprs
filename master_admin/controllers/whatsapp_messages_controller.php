<?php
  class Whatsapp_messagesController extends CrudController{
    public $add_models = array("whatsapp_conversations","whatsapp_messages");

    protected function init_setup($action){
        return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'last_message_time'
        );
        $whatsapp_messages = Whatsapp_messages::get_list($filter_arr,"*",$payload);
        $conversation_id = $_REQUEST['conversation_id'];
        $this->data['whatsapp_conversation'] = Whatsapp_conversations::get_by_id($conversation_id);
        $this->data['whatsapp_messages'] = $whatsapp_messages;
        $this->include_view('whatsapp_messages/list.php');

    }

    protected function get_base_filter(){
        $conversation_id = $_REQUEST['conversation_id'];
        $filter_arr = array( 'conversation_id'=>$conversation_id);  
        return $filter_arr;     
    }

    public function edit(){
        return parent::edit();
    }

    public function updateSend(){
        return parent::updateSend();
    }

    public function add(){
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'id'
        );
        $whatsapp_messages = Whatsapp_messages::get_list($filter_arr,"*",$payload);
        $conversation_id = $_REQUEST['conversation_id'];
        $this->data['whatsapp_conversation'] = Whatsapp_conversations::get_by_id($conversation_id);
        $this->data['whatsapp_messages'] = $whatsapp_messages;
        
        return parent::add();
    }       

    public function createSend(){
        return parent::createSend();
    }

    public function delete(){
        return parent::delete();      
    }

    public function include_edit_view(){
        $this->include_view('whatsapp_messages/edit.php');
    }

    public function include_add_view(){
        $this->include_view('whatsapp_messages/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("ההודעה עודכנה בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("ההודעה נוצרה בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("ההודעה נמחקה");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחרה שיחה");
    }   

    protected function delete_item($row_id){
      return Whatsapp_messages::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Whatsapp_messages::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('whatsapp_conversations/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("whatsapp_messages/add/?conversation_id=".$item_info['conversation_id']);
    }

    public function delete_url($item_info){
        return inner_url("whatsapp_messages/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return Whatsapp_messages::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Whatsapp_messages::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        print_r_help($fixed_values);
        $message_data = array(
            'conversation_id'=>$_REQUEST['conversation_id'],
            'message_text'=>$fixed_values['message_text'],
            'message_type'=>$fixed_values['message_type'],
        );
        $message_send = $this->call_module('whatsapp_messages','send_message',$message_data);

        return Whatsapp_messages::create($fixed_values);
    }
  }
?>