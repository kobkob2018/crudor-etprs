<?php
  class Whatsapp_templatesController extends CrudController{
    public $add_models = array("whatsapp_templates");

    protected function init_setup($action){
        return parent::init_setup($action);
    }  

    public function list(){
        //if(session__isset())
        
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'label'
        );
        $whatsapp_templates_list = Whatsapp_templates::get_list($filter_arr,"*", $payload);      
        $this->data['whatsapp_templates_list'] = $whatsapp_templates_list;
        $this->include_view('whatsapp_templates/list.php');
    }

    public function ajax_fetch(){
        $template_id = $_REQUEST['templae_id'];
        $template = Whatsapp_templates::get_by_id($template_id);

        $info = array('template'=>$template);
        $this->set_layout("blank");
        return $this->include_view("whatsapp_templates/ajax_fetch.php",$info);
    }

    protected function get_base_filter(){
        $filter_arr = array();  
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
        $this->include_view('whatsapp_templates/edit.php');
    }

    public function include_add_view(){
        $this->include_view('whatsapp_templates/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("התבנית עודכנה בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("התבנית נוצרה בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("התבנית נמחקה");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחרה תבנית");
    }   

    protected function delete_item($row_id){
      return Whatsapp_templates::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Whatsapp_templates::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('whatsapp_templates/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("whatsapp_templates/edit/?row_id=".$item_info['id']);
    }

    protected function after_delete_redirect(){
        return $this->redirect_to(inner_url("/whatsapp_templates/list/"));
    }

    public function delete_url($item_info){
        return inner_url("whatsapp_templates/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
        return Whatsapp_templates::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Whatsapp_templates::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        return Whatsapp_templates::create($fixed_values);
    }
  }
?>