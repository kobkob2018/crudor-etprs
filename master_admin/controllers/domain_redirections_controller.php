<?php
class Domain_redirectionsController extends CrudController{
  public $add_models = array("masterDomain_redirections");


  protected function init_setup($action){
    $current_item_id = '0';
    if(isset($_GET['row_id'])){
        $current_item_id = $_GET['row_id'];
    }
    $this->data['current_item_id'] = $current_item_id;

    return parent::init_setup($action);
  }

  public function list(){
    
    $filter_arr = $this->get_base_filter();
    $payload = array(
        'order_by'=>'label'
    );
    $item_list = MasterDomain_redirections::get_list($filter_arr,"*", $payload);
    $this->data['item_list'] = $this->prepare_forms_for_all_list($item_list);

    //for the add item form
    $form_handler = $this->init_form_handler();
    $form_handler->update_fields_collection($this->get_fields_collection());
    
    
    $this->include_view('domain_redirections/list_items.php');
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

  protected function update_success_message(){
    SystemMessages::add_success_message("ההפנייה עודכנה בהצלחה");

  }

  protected function create_success_message(){
    SystemMessages::add_success_message("ההפנייה נוצרה בהצלחה");

  }

  protected function delete_success_message(){
    SystemMessages::add_success_message("ההפנייה נמחקה");
  }

  protected function row_error_message(){
    SystemMessages::add_err_message("לא נבחרה הפנייה");
  }   

  protected function delete_item($row_id){
    return MasterDomain_redirections::delete($row_id);
  }

  protected function get_item_info($row_id){
    return MasterDomain_redirections::get_by_id($row_id);
  }

  protected function after_delete_redirect(){
    return $this->redirect_to(inner_url("domain_redirections/list/"));
  }

  protected function after_add_redirect($new_row_id){
    return $this->redirect_to(inner_url("domain_redirections/list/"));
  }

  public function eject_url(){
    return inner_url("domain_redirections/list/");
  }

  public function url_back_to_item($item_info){
    return inner_url("domain_redirections/list/");
  }

  public function delete_url($item_info){
    return inner_url("domain_redirections/delete/?&row_id=".$item_info['id']);
  }

  protected function get_fields_collection(){
    return MasterDomain_redirections::setup_field_collection();
  }

  protected function update_item($item_id,$update_values){
    return MasterDomain_redirections::update($item_id,$update_values);
  }

  protected function create_item($fixed_values){
    return MasterDomain_redirections::create($fixed_values);
  }
}
?>