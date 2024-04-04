<?php
  class Whatsapp_messagesController extends CrudController{
    public $add_models = array("whatsapp_conversations","whatsapp_messages","whatsapp_messages_errors");

    protected function init_setup($action){
        return parent::init_setup($action);
    }

    public function ajax_list(){
      //the original list is in the add function
      $this->set_layout("blank");
      $filter_arr = $this->get_base_filter();
      $last_message_id = $_REQUEST['last_message_id'];
      $filter_arr['custom_time'] = array('custom_where'=>"id > $last_message_id");
      $payload = array(
          'order_by'=>'id desc'
      );
      $conversation_id = $filter_arr['conversation_id'];
      $last_err_viewed = $_REQUEST['last_err'];
      $messages_errors = Whatsapp_messages_errors::get_conversation_new_errors($conversation_id, $last_err_viewed);
      $this->data['messages_errors'] = $messages_errors;
      $whatsapp_messages = Whatsapp_messages::get_list($filter_arr,"*",$payload);
      $this->data['whatsapp_messages'] = $whatsapp_messages;
      
      $this->data['last_err'] = Whatsapp_messages_errors::get_last_error_id($conversation_id);
      $messages_html = $this->include_ob_view('whatsapp_messages/ajax_list.php');
      $return_array = array('messages_html'=>$messages_html);
      print(json_encode($return_array));
      return;
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
      $this->add_model("whatsapp_templates");
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'id desc'
        );
        $whatsapp_messages = Whatsapp_messages::get_list($filter_arr,"*",$payload);
        $conversation_id = $_REQUEST['conversation_id'];
        $this->data['whatsapp_conversation'] = Whatsapp_conversations::get_by_id($conversation_id);
        $bot_state = json_decode($this->data['whatsapp_conversation']['bot_state']);
        $bot_state_checkboxes = array();
        foreach($bot_state as $key=>$value){
          $checked = "";
          if($value == '1'){
            $checked = "checked";
          }
          $bot_state_checkboxes[$key] = array(
            'checked'=>$checked
          );
        }
        $this->data['bot_state_checkboxes'] = $bot_state_checkboxes;
        $this->data['whatsapp_messages'] = $whatsapp_messages;
        $this->data['last_err'] = Whatsapp_messages_errors::get_last_error_id($conversation_id);
        $this->data['templates'] = Whatsapp_templates::get_list();
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
        $message_data = array(
            'conversation_id'=>$_REQUEST['conversation_id'],
            'message_text'=>$fixed_values['message_text'],
            'image_link'=>$fixed_values['image_link'],
            'video_link'=>$fixed_values['video_link'],
            'message_type'=>'text',
            'template_language'=>'none', //deprecated, we dont use the whatsapp teplates, we use our own..
        );
        if($fixed_values['image_link'] != ''){
          $message_data['message_type'] = 'image';
        }
        if($fixed_values['video_link'] != ''){
          $message_data['message_type'] = 'video';
        }
        $bot_state = array(
          'auto_reply'=>'0',
          'info_collect'=>'0',
          'admin_alerts'=>'0',
        );
        foreach($bot_state as $key=>$val){
          if(isset($_REQUEST[$key]) && $_REQUEST[$key] == '1'){
            $bot_state[$key] = '1';
          }
        }
        $bot_state_json = json_encode($bot_state);
        
        $conversation_id = $_REQUEST['conversation_id'];
        Whatsapp_conversations::update($conversation_id,array('bot_state'=>$bot_state_json));
        $message_send = $this->call_module('whatsapp_messages','send_message',$message_data);
        return $message_send;
    }
  }
?>