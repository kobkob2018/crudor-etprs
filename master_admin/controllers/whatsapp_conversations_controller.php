<?php
  class Whatsapp_conversationsController extends CrudController{
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
        $whatsapp_conversations = Whatsapp_conversations::get_list($filter_arr,"*",$payload);


        foreach($whatsapp_conversations as $key=>$val){
          $whatsapp_conversations[$key]['last_message'] = Whatsapp_messages::get_by_id($val['last_message_id']);
        }
        $this->data['whatsapp_conversations'] = $whatsapp_conversations;
        $this->include_view('whatsapp_conversations/list.php');

    }

    protected function get_base_filter(){
    
        $filter_arr = array( );  
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
        $this->include_view('whatsapp_conversations/edit.php');
    }

    public function include_add_view(){
        $this->include_view('whatsapp_conversations/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("השיחה עודכנה בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("השיחה נוצרה בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("השיחה נמחקה");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחרה שיחה");
    }   

    protected function delete_item($row_id){
      return Whatsapp_conversations::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Whatsapp_conversations::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('whatsapp_conversations/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("whatsapp_conversations/edit/?row_id=".$item_info['id']);
    }

    public function delete_url($item_info){
        return inner_url("whatsapp_conversations/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return Whatsapp_conversations::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Whatsapp_conversations::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        return Whatsapp_conversations::create($fixed_values);
    }
  }
?>