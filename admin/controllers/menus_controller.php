<?php
class MenusController extends CrudController{
  public $add_models = array("adminMenuItems");

  public $menu_action_identifiers =  array(
    "hero"=>"hero_menu",
    "top"=>"top_menu",
    "right"=>"right_menu",
    "bottom"=>"bottom_menu",
  );

  protected function init_setup($action){
    $this->data['site'] = Sites::get_user_workon_site();
    $current_item_id = '0';
    if(isset($_GET['row_id'])){
        $current_item_id = $_GET['row_id'];
        $item_parent_tree = AdminMenuItems::get_item_parents_tree($current_item_id,'id, label');
        $this->data['item_parent_tree'] = $item_parent_tree;
    }
    $this->data['current_item_id'] = $current_item_id;

    return parent::init_setup($action);
  }

  public function right_menu(){
      $this->data['action_name'] = 'right_menu';
      $this->data['page_title'] = "תפריט ימני";
      $this->data['menu_identifier'] = 'right';
      return $this->list();
  }

  public function top_menu(){
      $this->data['action_name'] = 'top_menu';
      $this->data['page_title'] = "תפריט עליון";
      $this->data['menu_identifier'] = 'top';
      return $this->list();
  }

  public function bottom_menu(){
      $this->data['action_name'] = 'bottom_menu';
      $this->data['page_title'] = "תפריט תחתון";
      $this->data['menu_identifier'] = 'bottom';
      return $this->list();
  }

  public function hero_menu(){
      $this->data['action_name'] = 'hero_menu';
      $this->data['page_title'] = "תפריט הירו";
      $this->data['menu_identifier'] = 'hero';
      return $this->list();
  }

  protected function get_item_parents_tree($parent_id,$select_params){        
      return AdminMenuItems::get_item_parents_tree($parent_id,$select_params);
  }

  public function list(){
    if(isset($_REQUEST['move_item'])){
      return $this->move_item_prepare($_REQUEST['move_item']);
    }

    $this->get_move_item_session();
    //if(session__isset())
    $filter_arr = $this->get_base_filter();
    $filter_arr['parent'] = $this->data['current_item_id'];
    $filter_arr['menu_id'] = AdminMenuItems::$menu_type_list[$this->data['menu_identifier']];
    $payload = array(
        'order_by'=>'priority'
    );
    $item_list = AdminMenuItems::get_list($filter_arr,"*", $payload);
    $this->data['item_list'] = $this->prepare_forms_for_all_list($item_list);

    //for the add item form
    $form_handler = $this->init_form_handler();
    $form_handler->update_fields_collection($this->get_fields_collection());
    
    
    $this->include_view('menus/list_items.php');
  }

  protected function get_base_filter(){
    
      $filter_arr = array('site_id'=>$this->data['work_on_site']['id']);  
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
    $menu_identifier = $_REQUEST['menu_identifier'];
    $this->data['menu_identifier'] = $menu_identifier;
    $this->data['action_name'] = $this->menu_action_identifiers[$menu_identifier];
    return parent::createSend();
  }

  public function delete(){
    $menu_identifier = $_REQUEST['menu_identifier'];
    $this->data['menu_identifier'] = $menu_identifier;
    $this->data['action_name'] = $this->menu_action_identifiers[$menu_identifier];
    return parent::delete();      
  }

  protected function update_success_message(){
    SystemMessages::add_success_message("הקטגוריה עודכנה בהצלחה");

  }

  protected function create_success_message(){
    SystemMessages::add_success_message("הקטגוריה נוצרה בהצלחה");

  }

  protected function delete_success_message(){
    SystemMessages::add_success_message("הקטגוריה נמחקה");
  }

  protected function row_error_message(){
    SystemMessages::add_err_message("לא נבחרה קטגוריה");
  }   

  protected function delete_item($row_id){
    return AdminMenuItems::delete_with_offsprings($row_id);
  }

  protected function get_item_info($row_id){
    return AdminMenuItems::get_by_id($row_id);
  }

  protected function after_delete_redirect(){

    $parent = '0';
    $row_url = '';
    if($this->data['item_info']){
      if(isset($this->data['item_info']['parent'])){
        $parent = $this->data['item_info']['parent'];
      }
    }
    if($parent != '0'){
      $row_url = "?row_id=".$parent;
    }
    return $this->redirect_to(inner_url("menus/".$this->data['action_name'].$row_url));
  }

  protected function after_add_redirect($new_row_id){
    $row_url = "";
    if(isset($_REQUEST['row_id']) && $_REQUEST['row_id'] != '0'){
      $row_url = "?row_id=".$_REQUEST['row_id'];
    }
    return $this->redirect_to(inner_url("menus/".$this->data['action_name'].$row_url));
  }

  public function eject_url(){
    return inner_url("menus/".$this->data['action_name']."/");
  }

  public function url_back_to_item($item_info){
    return inner_url("menus/".$this->data['action_name']."/?row_id=".$item_info['id']);
  }

  public function delete_url($item_info){
    return inner_url("menus/delete/?menu=".$this->data['action_name']."&row_id=".$item_info['id']);
  }

  protected function get_fields_collection(){
    return AdminMenuItems::setup_field_collection();
  }

  protected function update_item($item_id,$update_values){
    return AdminMenuItems::update($item_id,$update_values);
  }

  protected function create_item($fixed_values){
      $parent = '0';
      if(isset($_REQUEST['row_id'])){
          $parent = $_REQUEST['row_id'];
      }
      if(isset($_REQUEST['parent'])){
          $parent = $_REQUEST['parent'];
      }
      $fixed_values['site_id'] = $this->data['work_on_site']['id'];
      $fixed_values['parent'] = $parent;
      $fixed_values['menu_id'] = AdminMenuItems::$menu_type_list[$this->data['menu_identifier']];
      return AdminMenuItems::create($fixed_values);
  }
}
?>