<?php
  class UsersController extends CrudController{

    public $add_models = array("users");

    protected function init_setup($action){
        
        if(isset($_GET['row_id'])){
            
        }
        return parent::init_setup($action);
    }


    public function list(){
      
        $filter_arr = array();
        $users = Users::get_list($filter_arr, '*');
        $this->data['users'] = $users;
        $this->data['fields_collection'] = Users::setup_field_collection();
        $this->include_view('users/list.php');

    }

    public function select_cat_city(){
      $this->data['add_leads_menu'] = true;
      $this->add_model('user_cat_city');
      $assign_info = User_cat_city::$tree_select_info;
      $cat_id = $_REQUEST['cat_id'];

      $assign_info['cat_id'] = $cat_id;
      $this->add_model('biz_categories');
      $this->data['cat_parent_tree'] = Biz_categories::get_item_parents_tree($cat_id,'id,label,parent');
      $this->setup_tree_select_info($assign_info);
      if(isset($this->data['item_info'])){
          $this->data['user_info'] = $this->data['item_info'];
      }
      $this->include_view("users/select_cat_city.php");
    }

    public function assign_to_item_for_cat_city($row_id, $selected_assigns){
      $cat_id = $this->data['assign_info']['cat_id'];
      $this->add_model('user_cat_city');
      User_cat_city::assign_cities_cats_and_item($row_id, $cat_id, $selected_assigns);
  }

  public function get_assign_item_offsprings_tree_for_cat_city($payload){
      $this->add_model('cities');
      return Cities::simple_get_item_offsprings_tree('0','id, label, parent',array(), $payload);
  }

  public function get_item_assign_list_for_cat_city($row_id){
      $cat_id = $this->data['assign_info']['cat_id'];
      $this->add_model("user_cat_city");
      return User_cat_city::get_item_cat_city_list($row_id, $cat_id);
  }

  public function add_assign_success_message_for_cat_city(){
      SystemMessages::add_success_message("הערים שוייכו בהצלחה לקטגוריה");
  }


    public function select_cats(){
        $this->data['add_leads_menu'] = true;
        $this->add_model('user_cat');
        $this->setup_tree_select_info(User_cat::$tree_select_info);
        if(isset($this->data['item_info'])){
            $this->data['user_info'] = $this->data['item_info'];
        }
        $this->include_view("users/select_cats.php");
    }


    public function add_recursive_assign_select_view_for_cat($assign_tree_item){
      $open_state_class = "closed";
      if($assign_tree_item['open_state']){
          $open_state_class = "open";
      }
      $assign_tree_item['open_class'] = $open_state_class;
      $this->include_view("users/select_assigns_children_for_cat_with_links.php",array('item'=>$assign_tree_item));
    }

    public function assign_to_item_for_cat($row_id,$selected_assigns){
        $this->add_model('user_cat');   
        User_cat::assign_cats_to_item($row_id,$selected_assigns);
    }



    public function get_assign_item_offsprings_tree_for_cat($payload){
        $this->add_model('biz_categories');
        return Biz_categories::simple_get_item_offsprings_tree('0','id, label, parent',array(), $payload);
    }

    public function get_item_assign_list_for_cat($row_id){
        $this->add_model("user_cat");
        return User_cat::get_item_cat_list($row_id);
    }

    public function add_assign_success_message_for_cat(){
        SystemMessages::add_success_message("הקטגוריות שוייכו בהצלחה");
    }

    public function select_cities(){
        $this->data['add_leads_menu'] = true;
        $this->add_model('user_city');
        $this->setup_tree_select_info(User_city::$tree_select_info);
        if(isset($this->data['item_info'])){
            $this->data['user_info'] = $this->data['item_info'];
        }
        $this->include_view("users/select_cities.php");
    }   

    public function assign_to_item_for_city($row_id,$selected_assigns){
        $this->add_model('user_city');
        User_city::assign_cities_to_item($row_id,$selected_assigns);
    }

    public function get_assign_item_offsprings_tree_for_city($payload){
        $this->add_model('cities');
        return Cities::simple_get_item_offsprings_tree('0','id, label, parent',array(), $payload);
    }

    public function get_item_assign_list_for_city($row_id){
        $this->add_model("user_city");
        return User_city::get_item_city_list($row_id);
    }

    public function add_assign_success_message_for_city(){
        SystemMessages::add_success_message("הערים שוייכו בהצלחה");
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
        if(!isset($_GET['row_id'])){
            $this->eject_redirect();
        }
        
        if(!isset($_REQUEST['confirm_delete'])){
            $this->data['user_info'] = Users::get_by_id($_GET['row_id']);
            $this->include_view('users/confirm_delete.php');
            return;
        }
        return $this->delete_final();
    }

    public function delete_final(){
        if(isset($_GET['row_id'])){
            $this->add_model("user_cat");
            User_cat::delete_item_assignments($_GET['row_id']);
            $this->add_model("user_city");
            User_city::delete_item_assignments($_GET['row_id']);
        }
        return parent::delete();  
    }

    public function validate_by_password($value, $validate_payload){

        global $action;

        $return_array =  array(
            'success'=>true
        );

        if($value == '' && $action == 'add'){
            $return_array['success'] = false;
            $return_array['message'] = "יש לבחור סיסמא חזקה יותר";
            return $return_array;
        }

        if($value == '' && $action == 'edit'){
            $return_array['fixed_value'] = $this->data['item_info'][$validate_payload['key']];
            return $return_array;
            
        }

        $password_confirm = $_REQUEST['row'][$validate_payload['key'].'_confirm'];

        if($value != $password_confirm){
            $return_array['success'] = false;
            $return_array['message'] = "הסיסמאות אינן תואמות";
            return $return_array;
        }

        if(strlen($value) < 6 ){
            $return_array['success'] = false;
            $return_array['message'] = "יש לבחור סיסמא חזקה יותר";
            return $return_array;
        }



        $return_array['fixed_value'] = md5($value);



        return $return_array;
    }

    public function include_edit_view(){
      if(isset($this->data['item_info'])){
        $this->data['user_info'] = $this->data['item_info'];
      }
      $this->include_view('users/edit.php');
    }

    public function include_add_view(){
      $this->include_view('users/add.php');
    }   

    protected function update_success_message(){
      SystemMessages::add_success_message("הפרטים עודכנו בהצלחה");

    }

    protected function create_success_message(){
      SystemMessages::add_success_message("המשתמש נוצר בהצלחה");

    }

    protected function delete_success_message(){
      SystemMessages::add_success_message("המשתמש נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר משתמש");
    }   

    protected function delete_item($row_id){
      return Users::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Users::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('users/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("users/edit/?row_id=".$item_info['id']);
    }

    public function delete_url($item_info){
      return inner_url("users/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return Users::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Users::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
      return Users::create($fixed_values);
    }
  }
?>