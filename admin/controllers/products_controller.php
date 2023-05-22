<?php
  class ProductsController extends CrudController{
    public $add_models = array("products","product_cat");

    protected function init_setup($action){
        return parent::init_setup($action);
    }  

    public function assign_subs(){
        $this->add_model("product_sub_assign");
        $this->add_model("product_sub");
        
        if(!isset($_GET['row_id'])){
            return $this->eject_redirect();
        }
        $this->data['item_info'] = $this->get_item_info($_GET['row_id']);
        $this->data['product_info'] = $this->data['item_info'];
        if(isset($_REQUEST['submit_assign'])){
            
            $assign_subs = array();
            
            foreach($_REQUEST['assign'] as $sub){
                if($sub != '-1'){
                    $assign_subs[] = $sub;
                }
            }
            Product_sub_assign::assign_subs_to_item($this->data['item_info']['id'],  $assign_subs);      
            SystemMessages::add_success_message("התיקיות שוויכו בהצלחה");
            return $this->redirect_to(inner_url("products/assign_subs/?row_id=".$this->data['item_info']['id'])); 
        }
        $filter_arr = $this->get_base_filter();
        $sub_list = Product_sub::get_list($filter_arr,"id, label");
        $subs_assigned = Product_sub_assign::get_assigned_subs_to_item($this->data['item_info']['id']);
        $subs_checked_list = array();
        foreach($subs_assigned as $sub){
            $subs_checked_list[$sub['sub_id']] = '1';
        }
        $check_options = array();
        foreach($sub_list as $sub){
            $checked = "";
            if(isset($subs_checked_list[$sub['id']])){
                $checked = "checked";
            }
            $check_options[] = array('value'=>$sub['id'],'title'=>$sub['label'],'checked'=>$checked);
        }
        $info = array('options'=>$check_options);
        $this->include_view('products/sub_assign_form.php',$info);
    }

    public function list(){
        //if(session__isset())
        
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'label'
        );
        $product_list = Products::get_list($filter_arr,"*", $payload);      
        $this->data['product_list'] = $product_list;
        $this->include_view('products/list.php');
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
        $this->data['product_info'] = $this->data['item_info'];
        $this->include_view('products/edit.php');
    }

    public function include_add_view(){
        $this->include_view('products/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("המוצר עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("המוצר נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("המוצר נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר מוצר");
    }   

    protected function delete_item($row_id){
      return Products::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Products::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('products/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("products/edit/?row_id=".$item_info['id']);
    }

    protected function after_delete_redirect(){
        return $this->redirect_to(inner_url("/products/list/"));
    }

    public function delete_url($item_info){
        return inner_url("products/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
        return Products::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Products::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        $fixed_values['site_id'] = $this->data['work_on_site']['id'];
        return Products::create($fixed_values);
    }
  }
?>