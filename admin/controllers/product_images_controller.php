<?php
  class Product_imagesController extends CrudController{
    public $add_models = array("product_images","products");

    protected function init_setup($action){
        $product_id = $this->add_product_info_data();
        if(!$product_id){
            return $this->redirect_to(inner_url("products/list/"));
        }
        
        return parent::init_setup($action);
    }

    public function combine_add_and_list(){
        
        //if(session__isset())
        $fields_collection = Product_images::setup_field_collection();
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'priority'
        );
        $images_list = Product_images::get_list($filter_arr,"*", $payload);
        $this->data['images_list'] = $this->prepare_forms_for_all_list($images_list,$fields_collection,"product_image_");
        $this->include_view('product_images/add_and_list.php');
    }

    protected function add_product_info_data(){

        if(!isset($_GET['product_id'])){
            return false;
        }
        $product_id = $_GET['product_id'];
        $product_info = Products::get_by_id($product_id, 'id, label');
        $this->data['product_info'] = $product_info;
        if($product_info && isset($product_info['id'])){
            return $product_info['id'];
        }
    }

    protected function get_base_filter(){
        $product_id = $this->add_product_info_data();
        if(!$product_id){
            return;
        }

        $filter_arr = array(
            'product_id'=>$product_id,
    
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

    public function include_add_view(){
        return $this->combine_add_and_list();
        //return $this->include_view('product_images/add.php');
    }

    protected function update_success_message(){
        SystemMessages::add_success_message("התמונה עודכנה בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("התמונה נוספה בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("התמונה נמחקה");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחרה תמונה");
    }   

    protected function delete_item($row_id){
      return Product_images::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Product_images::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('product_images/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("product_images/add/?product_id=".$this->data['product_info']['id']);
    }

    protected function after_delete_redirect(){
        return $this->redirect_to(inner_url("product_images/add/?product_id=".$this->data['product_info']['id']));
    }

    protected function after_add_redirect($item_id){
        return $this->redirect_to(inner_url("product_images/add/?product_id=".$this->data['product_info']['id']));
    }

    public function delete_url($item_info){
        return inner_url("product_images/delete/?product_id=".$this->data['product_info']['id']."&row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
        return Product_images::setup_field_collection();
    }

    protected function create_item($fixed_values){
        $fixed_values['product_id'] = $this->data['product_info']['id'];
        return Product_images::create($fixed_values);
    }

    protected function update_item($item_id,$update_values){
        return Product_images::update($item_id,$update_values);
    }

  }
?>