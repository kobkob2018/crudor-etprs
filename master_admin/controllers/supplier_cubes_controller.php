<?php
  class supplier_cubesController extends CrudController{
    public $add_models = array("supplier_cubes");

    protected function init_setup($action){
        return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'label'
        );
        $supplier_cubes = Supplier_cubes::get_list($filter_arr,"*", 'id, insert_date , label, active, views, clicks, convertions ');
        $this->data['fields_collection'] = Supplier_cubes::setup_field_collection();
        $this->data['supplier_cubes'] = $supplier_cubes;
        $this->include_view('supplier_cubes/list.php');

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

    public function set_priority(){
        return parent::set_priority();
    }

    public function include_edit_view(){
        $this->include_view('supplier_cubes/edit.php');
    }

    public function include_add_view(){
        $this->include_view('supplier_cubes/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("הספק עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("הספק נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("הספק נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר ספק");
    }   

    protected function delete_item($row_id){
      return Supplier_cubes::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Supplier_cubes::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('supplier_cubes/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("supplier_cubes/edit/?row_id=".$item_info['id']);
    }

    public function delete_url($item_info){
        return inner_url("supplier_cubes/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return Supplier_cubes::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Supplier_cubes::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        return Supplier_cubes::create($fixed_values);
    }
  }
?>