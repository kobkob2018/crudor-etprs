<?php
  class Whatsapp_notificationsController extends CrudController{
    public $add_models = array("whatsapp_notifications");

    protected function init_setup($action){
        return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();

        $list_info = $this->get_paginated_list_info($filter_arr,array('page_limit'=>'300'));
        
        $whatsapp_notifications = array();

        foreach($list_info['list'] as $key=>$val){
            $val_arr = json_decode($val['info'],true);
            $whatsapp_notifications[$val['id']] = $this->create_list_item_from_array("notification",$val_arr);
            $whatsapp_notifications[$val['id']]['notification_time'] = $val['notification_time'];
        }
        $this->data['whatsapp_notifications'] = $whatsapp_notifications;
        $this->include_view('whatsapp_notifications/list.php',array('list'=>$whatsapp_notifications,'filter_form'=>$list_info['filter_form']));
    }

    protected function create_list_item_from_array($key,$item, $item_class="", $item_array = array('type'=>'none','values'=>array())){
        $item_class = $item_class." ".$key;
        if(!is_array($item)){
            if($key == 'type'){
                $item_array['type'] = $item;
            }
            $item_array['values'][] = array('class'=>$item_class,'value'=>$item,'key'=>$key);
        }
        else{
            foreach($item as $item_key=>$item_val){
                $item_array = $this->create_list_item_from_array($item_key,$item_val,$item_class,$item_array);
            }
        }
        return $item_array;
    }

    protected function get_paginated_list($filter_arr, $payload){
        $payload['order_by'] = $this->session_order_by.", id";
        return Whatsapp_notifications::get_list($filter_arr, '*',$payload);
    }

    public function clear_notifications_table(){
        Whatsapp_notifications::clear_list();
        SystemMessages::add_success_message("list is clear");
        $this->redirect_to(inner_url("whatsapp_notifications/list/"));
    }

    public function clear_old(){ 
        Whatsapp_notifications::clear_old();
        SystemMessages::add_success_message("list is clear from old notifications");
        $this->redirect_to(inner_url("whatsapp_notifications/list/"));
    }

    public function view_log_file(){
        $contents = file_get_contents('assets_s/logs/webhooks_notes.txt');
        $contents = nl2br($contents);
        print($contents);
    }

    protected function get_filter_fields_collection(){
        return array();
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
        $this->include_view('whatsapp_notifications/edit.php');
    }

    public function include_add_view(){
        $this->include_view('whatsapp_notifications/add.php');
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
      return Whatsapp_notifications::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Whatsapp_notifications::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('whatsapp_notifications/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("whatsapp_notifications/edit/?row_id=".$item_info['id']);
    }

    public function delete_url($item_info){
        return inner_url("whatsapp_notifications/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return Whatsapp_notifications::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Whatsapp_notifications::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        return Whatsapp_notifications::create($fixed_values);
    }
  }
?>