<?php
  class Net_bannersController extends CrudController{
    public $add_models = array("net_banners","net_directories");

    protected function init_setup($action){
        $dir_id = $this->add_dir_info_data();
        if(!$dir_id){
            return $this->redirect_to(inner_url("net_directories/list/"));
            return false;
        }
        return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'label'
        );
        $net_banners = Net_banners::get_list($filter_arr,"*", 'id, insert_date , label, active, views, clicks, convertions ');
        $fields_collection = Net_banners::setup_field_collection();
        $active_strings = array();
        foreach($fields_collection['active']['options'] as $option){
          $active_strings[$option['value']] = $option['title'];
        }
        foreach($net_banners as $key=>$dir){
          $net_banners[$key]['active_str'] = $active_strings[$dir['active']];
        }
        $this->data['net_banners'] = $net_banners;
        $this->include_view('net_banners/list.php');

    }

    protected function add_dir_info_data(){

        if(!isset($_GET['dir_id'])){
            return false;
        }
        $dir_id = $_GET['dir_id']; 
        $dir_info = Net_directories::get_by_id($dir_id, 'id, label');
        $this->data['dir_info'] = $dir_info;
        if($dir_info && isset($dir_info['id'])){
            return $dir_info['id'];
        }
    }


    public function select_cats(){
        $this->add_model('net_banner_cat');
        $this->setup_tree_select_info(Net_banner_cat::$tree_select_info);

        $this->include_view("net_banners/select_cats.php");
    }

    public function assign_to_item_for_cat($row_id,$selected_assigns){
        $this->add_model('net_banner_cat');   
        Net_banner_cat::assign_cats_to_item($row_id,$selected_assigns);
    }

    public function assign_to_item_for_city($row_id,$selected_assigns){
        $this->add_model('net_banner_city');
        Net_banner_city::assign_cities_to_item($row_id,$selected_assigns);
    }

    public function get_assign_item_offsprings_tree_for_cat($payload){
        $this->add_model('biz_categories');
        return Biz_categories::simple_get_item_offsprings_tree('0','id, label, parent',array(), $payload);
    }

    public function get_assign_item_offsprings_tree_for_city($payload){
        $this->add_model('cities');
        return Cities::simple_get_item_offsprings_tree('0','id, label, parent',array(), $payload);
    }

    public function get_item_assign_list_for_cat($row_id){
        $this->add_model("net_banner_cat");
        return Net_banner_cat::get_item_cat_list($row_id);
    }

    public function get_item_assign_list_for_city($row_id){
        $this->add_model("net_banner_city");
        return Net_banner_city::get_item_cat_list($row_id);
    }

    public function add_assign_success_message_for_cat(){
        SystemMessages::add_success_message("הקטגוריות שוייכו בהצלחה");
    }


    public function add_assign_success_message_for_city(){
        SystemMessages::add_success_message("הערים שוייכו בהצלחה");
    }

    protected function get_base_filter(){
        $dir_id = $this->add_dir_info_data();
        if(!$dir_id){
            return;
        }

        $filter_arr = array(
            'dir_id'=>$dir_id,
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
        if(isset($_GET['row_id'])){
            $this->add_model("net_banner_cat");
            Net_banner_cat::delete_item_assignments($_GET['row_id']);
        }
        return parent::delete();      
    }

    public function set_priority(){
        return parent::set_priority();
    }

    public function include_edit_view(){
        $this->include_view('net_banners/edit.php');
    }

    public function include_add_view(){
        $this->include_view('net_banners/add.php');
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
      return Net_banners::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Net_banners::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('net_banners/list/?dir_id='.$this->data['dir_info']['id']);
    }

    public function url_back_to_item($item_info){
      return inner_url("net_banners/edit/?row_id=".$item_info['id']."&dir_id=".$this->data['dir_info']['id']);
    }

    public function delete_url($item_info){
        return inner_url("net_banners/delete/?row_id=".$item_info['id']."&dir_id=".$this->data['dir_info']['id']);
    }

    protected function get_fields_collection(){
      return Net_banners::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Net_banners::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        $fixed_values['dir_id'] = $this->data['dir_info']['id'];
        return Net_banners::create($fixed_values);
    }
  }
?>