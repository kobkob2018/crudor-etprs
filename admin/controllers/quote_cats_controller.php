<?php
  class Quote_catsController extends CrudController{ 
    public $add_models = array("quote_cat");

    protected function init_setup($action){
      return parent::init_setup($action);
    }

    public function list(){
      $this->add_model("user_quote_cat_enable");
      $this->add_model("site_users");
      $filter_arr = $this->get_base_filter();
      $payload = array(
          'order_by'=>'label'
      );
      $cat_list = Quote_cat::get_list($filter_arr,"*", $payload); 
      $quote_cat_labels = Helper::eazy_index_arr_by('id',$cat_list,'label');
      $this->data['cat_list'] = $cat_list;
      $info = array();
      $info['quote_cat_list_arr'] = $cat_list;
      $user_list = Site_users::get_site_users_that_can($this->data['work_on_site']['id'],'quotes');
      foreach($user_list as $key=>$user){

        $user_quote_cat_enable = User_quote_cat_enable::get_list(array('user_id'=>$user['id']),'cat_id');
        $user_cats = array();
        foreach($user_quote_cat_enable as $cat){
            $user_cats[$cat['cat_id']] = array('id'=>$cat['cat_id'],'label'=>$quote_cat_labels[$cat['cat_id']]);
        }
        $user_list[$key]['cats_assigned'] = $user_cats;
      }
      $this->data['user_list'] = $user_list;
      $this->include_view('quote_cats/list.php',$info);
    }


    protected function add_cat_info_data(){
      if(isset($this->data['item_info'])){
        $this->data['cat_info'] = $this->data['item_info'];
      }
      if(!isset($_GET['cat_id'])){
          return false;
      }
      $this->data['cat_info'] = Quote_cat::get_by_id($_GET['cat_id']);
    }

    protected function add_theme_list_data(){
      $this->data['cat_theme_list'] = Quote_cat::simple_get_list_by_table_name(array(),'quote_cat_theme');
    }

    public function delete(){
      $this->add_cat_info_data();
      $fields_collection = Quote_cat::setup_field_collection(Quote_cat::get_fields_collection_for_cat_delete($this->data['cat_info']['id']));
      
      $form_handler = $this->init_form_handler("main");
      $form_handler->update_fields_collection($fields_collection);
      
      $this->add_form_builder_data($fields_collection,'delete_confirm',$this->data['cat_info']['id']);  
      $this->include_view('quote_cats/delete_form.php');
    }

    public function assignUserSend(){
      if(!isset($_REQUEST['assign_user_id']) || !isset($_REQUEST['assign'])){
        return $this->redirect_to(inner_url("quote_cats/list/"));
      }
      $this->add_model("user_quote_cat_enable");
      $assign_cats = array();
      foreach($_REQUEST['assign'] as $cat){
          if($cat != '-1'){
              $assign_cats[] = $cat;
          }
      }   
      User_quote_cat_enable::assign_cats_to_item($_REQUEST['assign_user_id'], $assign_cats);  
      SystemMessages::add_success_message("הרשימות שוייכו אל הלקוח"); 
      return $this->redirect_to(inner_url("quote_cats/list/"));
    }

    public function delete_confirm(){
        $move_to_cat = $_REQUEST['row']['move_quotes_to'];
        Quote_cat::move_quotes_from_cat_to($this->data['cat_info']['id'],$move_to_cat);
        Quote_cat::delete($this->data['cat_info']['id']);
        SystemMessages::add_success_message("התיקייה נמחקה");
        return $this->eject_redirect();
    }  

    public function export_to_theme(){
      if(!isset($_REQUEST['row_id'])){
        return $this->redirect_to(inner_url("admin/quote_cats/list/"));
      }
      $cat_id = $_REQUEST['row_id'];
      if(!isset($_REQUEST['cat_label']) || $_REQUEST['cat_label'] == ""){
        SystemMessages::add_err_message("יש לבחור שם למבנה בחדש");
        return $this->redirect_to(inner_url("quote_cats/edit/?row_id=".$cat_id));
      }
      $quote_cat = Quote_cat::get_by_id($cat_id);
      $new_theme = array(
        'label'=>$_REQUEST['cat_label'],
        'custom_html'=>$quote_cat['custom_html'],
        'title_html'=>$quote_cat['title_html']
      );
      Quote_cat::simple_create_by_table_name($new_theme,'quote_cat_theme');
      SystemMessages::add_success_message("המבנה נוצר בהצלחה: ".$new_theme['label']);
      return $this->redirect_to(inner_url("quote_cats/edit/?row_id=".$cat_id));
    }

    public function import_theme_info(){
      $this->set_layout("blank");
      $return_array = array("success"=>"false");
      if(!isset($_REQUEST['theme_id'])){
       return print(json_encode($return_array,true));
      }
      $theme_id = $_REQUEST['theme_id'];
      $theme_info = Quote_cat::simple_find_by_table_name(array("id"=>$theme_id),"quote_cat_theme");
      if(!$theme_info){
        return print(json_encode($return_array,true));
      }
      $return_array['success'] = "true";
      $return_array['theme'] = $theme_info;
      return print(json_encode($return_array,true));
    }

    public function include_edit_view(){
      $this->add_cat_info_data();
      $this->add_theme_list_data();
      $this->include_view('quote_cats/edit.php');
    }

    public function include_add_view(){
      $this->add_theme_list_data();
      $this->include_view('quote_cats/add.php');
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
      return Quote_cat::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Quote_cat::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('quote_cats/list/');
    }

    protected function after_add_redirect($item_id){
      return $this->redirect_to(inner_url('quote_cats/edit/?row_id='.$item_id));
    }

    protected function after_edit_redirect($item_info){
      return $this->redirect_to(inner_url('quote_cats/edit/?row_id='.$item_info['id']));
    }

    protected function get_fields_collection(){
      return Quote_cat::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Quote_cat::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        return Quote_cat::create($fixed_values);
    }
  }
?>