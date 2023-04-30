<?php
  class Product_catsController extends CrudController{ 
    public $add_models = array("product_cat");

    protected function init_setup($action){
      return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'label'
        );
        $cat_list = Product_cat::get_list($filter_arr,"*", $payload); 
        $this->data['cat_list'] = $cat_list;
        $this->include_view('product_cats/list.php');
    }

    protected function get_base_filter(){
        $filter_arr = array(
            'site_id'=>$this->data['work_on_site']['id']
        );  
        return $filter_arr;      
    }

    protected function add_cat_info_data(){
        if(isset($this->data['item_info'])){
            $this->data['cat_info'] = $this->data['item_info'];
        }
        if(!isset($_GET['cat_id'])){
            return false;
        }
        $this->data['cat_info'] = Product_cat::get_by_id($_GET['cat_id']);
    }

    public function delete(){
        return parent::delete();
    }

  public function include_edit_view(){
    $this->add_cat_info_data();
    $this->include_view('product_cats/edit.php');
  }

  public function include_add_view(){
      $this->include_view('product_cats/add.php');
  }

  protected function create_success_message(){
    SystemMessages::add_success_message("התיקייה המחיר נוצרה בהצלחה");
  }

  protected function update_success_message(){
    SystemMessages::add_success_message("התיקייה עודכנה בהצלחה");
  }

  protected function delete_success_message(){
    SystemMessages::add_success_message("התיקייה נמחקה");
  }

  protected function row_error_message(){
    SystemMessages::add_err_message("לא נבחרה תיקייה");
  }   

  protected function delete_item($row_id){
    return Product_cat::delete($row_id);
  }

    protected function get_item_info($row_id){
      return Product_cat::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('product_cats/list/');
    }

    protected function after_add_redirect($item_id){
      return $this->redirect_to(inner_url('product_cats/list/'));
    }

    protected function after_edit_redirect($item_id){
      return $this->redirect_to(inner_url('product_cats/list/'));
    }

    protected function get_fields_collection(){
      return Product_cat::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Product_cat::update($item_id,$update_values);
    }

    public function delete_url($item_info){
        return inner_url("product_cats/delete/?row_id=".$item_info['id']);
    }

    protected function create_item($fixed_values){
        $fixed_values['site_id'] = $this->data['work_on_site']['id'];
        return Product_cat::create($fixed_values);
    }
  }
?>