<?php
class Cat_whatsapp_termsController extends CrudController{
  public $add_models = array("cat_whatsapp_terms","biz_categories");


  protected function init_setup($action){
    if(!isset($_REQUEST['cat_id'])){
        SystemMessages::add_err_message("לא נבחרה קטגוריה");
        return $this->redirect_to(inner_url("biz_categories/list/"));
    }
    $current_item_id = '0';
    if(isset($_REQUEST['cat_id'])){
        $current_item_id = $_REQUEST['cat_id'];
        $item_parent_tree = Biz_categories::get_item_parents_tree($current_item_id,'id, label');
        $this->data['item_parent_tree'] = $item_parent_tree;
    }
    $this->data['current_item_id'] = $current_item_id;
    $this->data['current_cat'] = Biz_categories::get_by_id($_REQUEST['cat_id']);
    
    return parent::init_setup($action);
  }


  public function list(){
    
      //if(session__isset())
      $filter_arr = $this->get_base_filter();

      $payload = array(
          'order_by'=>'term'
      );
      $term_list = Cat_whatsapp_terms::get_list($filter_arr,"*", $payload);
      $this->data['term_list'] = $this->prepare_forms_for_all_list($term_list);
      $this->include_view('cat_whatsapp_terms/list.php');
  }


  protected function get_base_filter(){
      $filter_arr = array('cat_id'=>$_REQUEST['cat_id']);
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
    $this->include_view('cat_whatsapp_terms/edit.php');
  }

  public function include_add_view(){
    $this->include_view('cat_whatsapp_terms/add.php');
  }   

  protected function update_success_message(){
    SystemMessages::add_success_message("המילה עודכנה בהצלחה");

  }

  protected function create_success_message(){
    SystemMessages::add_success_message("המילה נוצרה בהצלחה");

  }

  protected function delete_success_message(){
    SystemMessages::add_success_message("המילה נמחקה");
  }

  protected function row_error_message(){
    SystemMessages::add_err_message("לא נבחרה מילה");
  }   

  protected function delete_item($row_id){
    return Cat_whatsapp_terms::delete($row_id);
  }

  protected function get_item_info($row_id){
    return Cat_whatsapp_terms::get_by_id($row_id);
  }

  protected function after_delete_redirect(){
    return $this->eject_redirect();
  }

  protected function after_add_redirect($new_row_id){
    return $this->eject_redirect();
  }

  public function eject_url(){
    
    if(isset($_REQUEST['cat_id'])){
        return inner_url("cat_whatsapp_terms/list/?cat_id=".$_REQUEST['cat_id']);
    }
    return inner_url('biz_categories/list/');
  }

  public function url_back_to_item($item_info){
    return $this->eject_url();
  }

  public function delete_url($item_info){
    return inner_url("cat_whatsapp_terms/delete/?row_id=".$item_info['id']."&cat_id=".$item_info['cat_id']);
  }

  protected function get_fields_collection(){
    return Cat_whatsapp_terms::setup_field_collection();
  }

  protected function update_item($item_id,$update_values){
    return Cat_whatsapp_terms::update($item_id,$update_values);
  }

  protected function create_item($fixed_values){
      $cat_id = '0';
      if(isset($_REQUEST['cat_id'])){
          $cat_id = $_REQUEST['cat_id'];
      }
      
      $fixed_values['cat_id'] = $cat_id;
      return Cat_whatsapp_terms::create($fixed_values);
  }
}
?>