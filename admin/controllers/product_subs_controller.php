<?php
  class Product_subsController extends CrudController{ 
    public $add_models = array("product_sub");

    protected function init_setup($action){
      return parent::init_setup($action);
    }

    public function assign_cats(){
      $this->add_model("product_sub_cat_assign");
      $this->add_model("product_cat");
      
      if(!isset($_GET['row_id'])){
          return $this->eject_redirect();
      }
      $this->data['item_info'] = $this->get_item_info($_GET['row_id']);
      if(isset($_REQUEST['submit_assign'])){
          
          $assign_cats = array();
          
          foreach($_REQUEST['assign'] as $cat){
              if($cat != '-1'){
                  $assign_cats[] = $cat;
              }
          }
          Product_sub_cat_assign::assign_cats_to_item($this->data['item_info']['id'],  $assign_cats);      
          SystemMessages::add_success_message("התיקיות שוויכו בהצלחה");
          return $this->redirect_to(inner_url("product_subs/assign_cats/?row_id=".$this->data['item_info']['id'])); 
      }
      $filter_arr = $this->get_base_filter();
      $cat_list = Product_cat::get_list($filter_arr,"id, label");
      $cats_assigned = Product_sub_cat_assign::get_assigned_cats_to_item($this->data['item_info']['id']);
      $cats_checked_list = array();
      foreach($cats_assigned as $cat){
          $cats_checked_list[$cat['cat_id']] = '1';
      }
      $check_options = array();
      foreach($cat_list as $cat){
          $checked = "";
          if(isset($cats_checked_list[$cat['id']])){
              $checked = "checked";
          }
          $check_options[] = array('value'=>$cat['id'],'title'=>$cat['label'],'checked'=>$checked);
      }
      $info = array('options'=>$check_options);
      $this->include_view('product_subs/cat_assign_form.php',$info);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'label'
        );
        $cat_list = Product_sub::get_list($filter_arr,"*", $payload); 
        $this->data['cat_list'] = $cat_list;
        $this->include_view('product_subs/list.php');
    }

    protected function get_base_filter(){
        $filter_arr = array(
            'site_id'=>$this->data['work_on_site']['id']
        );  
        return $filter_arr;      
    }

    public function delete(){
        return parent::delete();
    }

    public function include_edit_view(){
        $this->include_view('product_subs/edit.php');
    }

  public function include_add_view(){
      $this->include_view('product_subs/add.php');
  }

  protected function create_success_message(){
    SystemMessages::add_success_message("תת התיקייה המחיר נוצרה בהצלחה");
  }

  protected function update_success_message(){
    SystemMessages::add_success_message("תת התיקייה עודכנה בהצלחה");
  }

  protected function delete_success_message(){
    SystemMessages::add_success_message("תת התיקייה נמחקה");
  }

  protected function row_error_message(){
    SystemMessages::add_err_message("לא נבחרה תת תיקייה");
  }   

  protected function delete_item($row_id){
    return Product_sub::delete($row_id);
  }

    protected function get_item_info($row_id){
      return Product_sub::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('product_subs/list/');
    }

    protected function after_add_redirect($item_id){
      return $this->redirect_to(inner_url('product_subs/assign_cats/?row_id='.$item_id));
    }

    protected function after_edit_redirect($item_id){
      return $this->redirect_to(inner_url('product_subs/list/'));
    }

    protected function get_fields_collection(){
      return Product_sub::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Product_sub::update($item_id,$update_values);
    }

    public function delete_url($item_info){
        return inner_url("product_subs/delete/?row_id=".$item_info['id']);
    }

    protected function create_item($fixed_values){
        $fixed_values['site_id'] = $this->data['work_on_site']['id'];
        return Product_sub::create($fixed_values);
    }
  }
?>