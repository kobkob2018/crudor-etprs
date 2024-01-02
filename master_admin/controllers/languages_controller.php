<?php
class LanguagesController extends CrudController{
  public $add_models = array("languages");


  protected function init_setup($action){
      if(isset($_GET['system_id'])){
          $this->data['system_id'] = $_GET['system_id'];
      }
      return parent::init_setup($action);
  }

  public function list(){
    $system_options = array(
        'master_admin'=>'ניהול ראשי',
        'admin'=>'ניהול אתרים',
        'sites'=>'אתרים',
        'myleads'=>'ניהול לידים'
    );
    $info = array(
        'system_options'=>$system_options
    );
    if(!isset($_REQUEST['system_id'])){
        return $this->include_view('languages/select_system.php',$info);
    }
    $info['system_id'] = $_REQUEST['system_id'];
    $info['system_id_label'] = $system_options[$info['system_id']];
    $filter_arr = $this->get_base_filter();
    $payload = array(
        'order_by'=>'label'
    );
    $language_list = Languages::get_list($filter_arr,"*", $payload);
    $this->data['language_list'] = $this->prepare_forms_for_all_list($language_list);

    $this->include_view('languages/list.php',$info);
  }

  protected function get_base_filter(){
    $filter_arr = array();
    if(isset($_REQUEST['system_id'])){
        $filter_arr['system_id'] = $_REQUEST['system_id'];
    }  
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
    $this->include_view('languages/edit.php');
  }

  public function include_add_view(){
    $this->include_view('languages/add.php');
  }   

  protected function update_success_message(){
    SystemMessages::add_success_message("השפה עודכנה בהצלחה");

  }

  protected function create_success_message(){
    SystemMessages::add_success_message("השפה נוצרה בהצלחה");

  }

  protected function delete_success_message(){
    SystemMessages::add_success_message("השפה נמחקה");
  }

  protected function row_error_message(){
    SystemMessages::add_err_message("לא נבחרה שפה");
  }   

  protected function delete_item($row_id){
    return Languages::delete($row_id);
  }

  protected function get_item_info($row_id){
    return Languages::get_by_id($row_id);
  }

  protected function after_delete_redirect(){
    return $this->eject_redirect();
  }

  protected function after_add_redirect($new_row_id){
      return $this->redirect_to(inner_url("language_messages/list/?language_id=$new_row_id"));
  }

  public function eject_url(){
    if(isset($this->data['system_id'])){
        return inner_url('languages/list/?system_id='.$this->data['system_id']);
    }
    return inner_url('languages/list/');
  }

  public function url_back_to_item($item_info){
    if(isset($this->data['system_id'])){
        return inner_url('languages/list/?system_id='.$this->data['system_id']);
    }
    return inner_url("languages/list/");
  }

  public function delete_url($item_info){
    return inner_url("languages/delete/?system_id=".$this->data['system_id']."&row_id=".$item_info['id']);
  }

  protected function get_fields_collection(){
    return Languages::setup_field_collection();
  }

  protected function update_item($item_id,$update_values){
    return Languages::update($item_id,$update_values);
  }

  protected function create_item($fixed_values){
    if(!isset($_REQUEST['system_id'])){
        SystemMessages::add_err_message("לא נבחרה מערכת");
        return $this->eject_redirect();
    }

    $fixed_values['system_id'] = $_REQUEST['system_id'];
    return Languages::create($fixed_values);
  }
}
?>