<?php
  class PagesController extends CrudController{
    public $add_models = array("sites","adminPages");
    public function error() {
      SystemMessages::add_err_message("Oops! seems like you are in the wrong place");
      $this->include_view('pages/error.php');
    }

    protected function init_setup($action){
      return parent::init_setup($action);
    }

    protected function handle_access($action){
      switch ($action){
        case 'error':
          return true;
          break;
        default:
          return parent::handle_access(($action));
          break;
        
      }
    }

		public function list(){
      
      $filter_arr = array('site_id'=>$this->data['work_on_site']['id']);
      $content_pages = AdminPages::get_list($filter_arr, 'id, title, link');
      
      $this->data['content_pages'] = $content_pages;

      $this->include_view('content_pages/list.php');

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
      if(isset($this->data['item_info'])){
        $this->data['page_info'] = $this->data['item_info'];
      }
      $this->include_view('content_pages/edit.php');
    }

    public function include_add_view(){
      $this->include_view('content_pages/add.php');
    }   

    protected function update_success_message(){
      SystemMessages::add_success_message("הדף עודכן בהצלחה");

    }

    protected function create_success_message(){
      SystemMessages::add_success_message("הדף נוצר בהצלחה");

    }

    protected function delete_success_message(){
      SystemMessages::add_success_message("הדף נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר דף");
    }   

    protected function delete_item($row_id){
      return AdminPages::delete($row_id);
    }

    protected function get_item_info($row_id){
      return AdminPages::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('pages/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("pages/edit/?row_id=".$item_info['id']);
    }

    public function delete_url($item_info){
      return inner_url("pages/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return AdminPages::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return AdminPages::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){

      $work_on_site = Sites::get_user_workon_site();
      $site_id = $work_on_site['id'];
      $fixed_values['site_id'] = $site_id;

      return AdminPages::create($fixed_values);
    }
  }
?>