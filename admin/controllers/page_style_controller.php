<?php
  class Page_styleController extends CrudController{
    public $add_models = array("sites","adminPages", "page_style", "biz_categories");

    protected function init_setup($action){
        $page_id = $this->add_page_info_data();
        if(!$page_id){
            return $this->redirect_to(inner_url("pages/list/"));
            return false;
        }
        return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $page_style = Page_style::get_list($filter_arr,"id");      
        $this->data['page_style'] = $page_style;
        $page_id = $_GET['page_id'];
        if(empty($page_style)){
            return $this->redirect_to(inner_url("page_style/add/?page_id=$page_id"));
        }
        else{
            $page_style_id = $page_style[0]['id'];
            return $this->redirect_to(inner_url("page_style/edit/?page_id=$page_id&row_id=$page_style_id"));
        }
        //$this->include_view('biz_forms/list.php');

    }

    protected function add_page_info_data(){

        if(!isset($_GET['page_id'])){
            return false;
        }
        $page_id = $_GET['page_id'];
        $page_info = AdminPages::get_by_id($page_id, 'id, title, link');
        $this->data['page_info'] = $page_info;
        if($page_info && isset($page_info['id'])){
            return $page_info['id'];
        }
    }

    protected function get_base_filter(){
        $page_id = $this->add_page_info_data();
        if(!$page_id){
            return;
        }

        $filter_arr = array(
            'site_id'=>$this->data['work_on_site']['id'],
            'page_id'=>$page_id,
    
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

    public function set_priority(){
        return parent::set_priority();
    }

    public function include_edit_view(){
        $this->include_view('page_style/edit.php');
    }

    public function include_add_view(){
        $this->include_view('page_style/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("העיצוב עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("העיצוב נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("העיצוב נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר עיצוב");
    }   

    protected function delete_item($row_id){
      return Page_style::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Page_style::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('page_style/list/?page_id='.$this->data['page_info']['id']);
    }

    public function url_back_to_item($item_info){
      return inner_url("page_style/list/?page_id=".$this->data['page_info']['id']);
    }

    protected function get_fields_collection(){
      return Page_style::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Page_style::update($item_id,$update_values);
    }

    protected function get_priority_space($filter_arr, $item_to_id){
        return Page_style::get_priority_space($filter_arr, $item_to_id);
      }

    protected function create_item($fixed_values){

        $work_on_site = Sites::get_user_workon_site();
        $site_id = $work_on_site['id'];
        $fixed_values['site_id'] = $site_id;
        $fixed_values['page_id'] = $this->data['page_info']['id'];
        return Page_style::create($fixed_values);
    }

  }
?>