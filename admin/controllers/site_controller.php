<?php
  class SiteController extends CrudController{
    public $add_models = array("adminSites","adminPages");

    protected function handle_access($action){
        
      return $this->call_module('admin','handle_access_user_is','master_admin');
    }

    public function edit(){
      $this->data['row_id'] = $this->data['work_on_site']['id'];
      return parent::edit();
    }

    public function updateSend(){
      return parent::updateSend();
    }

    public function include_edit_view(){
      if(isset($this->data['item_info'])){
        $this->data['site_info'] = $this->data['item_info'];
      }
      $this->include_view('site/edit_site.php');
    }

    protected function update_success_message(){
      SystemMessages::add_success_message("האתר עודכן בהצלחה");

    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר אתר");
    }   


    protected function get_item_info($row_id){
      return Sites::get_user_workon_site();
    }

    public function eject_url(){
      return inner_url('tasks/all/');
    }

    public function url_back_to_item($item_info){
      return inner_url("site/edit/");
    }

    public function delete_url($item_info){
      return inner_url("site/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return AdminSites::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Sites::update($item_id,$update_values);
    }

  }
?>