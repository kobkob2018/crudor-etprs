<?php
  class NewsController extends CrudController{
    public $add_models = array("news");

    protected function init_setup($action){
        return parent::init_setup($action);
    }  

    public function list(){
        //if(session__isset())
        
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'label'
        );
        $news_list = News::get_list($filter_arr,"*", $payload);      
        $this->data['news_list'] = $news_list;
        $this->include_view('news/list.php');
    }

    protected function get_base_filter(){
        $filter_arr = array(
            'site_id'=>$this->data['work_on_site']['id']
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
        $this->include_view('news/edit.php');
    }

    public function include_add_view(){
        $this->include_view('news/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("החדשה עודכנה בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("החדשה נוצרה בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("החדשה נמחקה");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחרה חדשה");
    }   

    protected function delete_item($row_id){
      return News::delete($row_id);
    }

    protected function get_item_info($row_id){
      return News::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('news/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("news/edit/?row_id=".$item_info['id']);
    }

    protected function after_delete_redirect(){
        return $this->redirect_to(inner_url("/news/list/"));
    }

    public function delete_url($item_info){
        return inner_url("news/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
        return News::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return News::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        $fixed_values['site_id'] = $this->data['work_on_site']['id'];
        return News::create($fixed_values);
    }
  }
?>