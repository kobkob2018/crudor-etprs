<?php
  class Quote_catsController extends CrudController{ 
    public $add_models = array("quote_cat");

    protected function init_setup($action){
      return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'label'
        );
        $cat_list = Quote_cat::get_list($filter_arr,"*", $payload); 
        $this->data['cat_list'] = $cat_list;
        $this->include_view('quote_cats/list.php');
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

  public function delete(){
    $this->add_cat_info_data();
    $fields_collection = Quote_cat::setup_field_collection(Quote_cat::get_fields_collection_for_cat_delete($this->data['cat_info']['id']));
    
    $form_handler = $this->init_form_handler("main");
    $form_handler->update_fields_collection($fields_collection);
    
    $this->add_form_builder_data($fields_collection,'delete_confirm',$this->data['cat_info']['id']);  
    $this->include_view('quote_cats/delete_form.php');
}

public function delete_confirm(){
    $move_to_cat = $_REQUEST['row']['move_quotes_to'];
    Quote_cat::move_quotes_from_cat_to($this->data['cat_info']['id'],$move_to_cat);
    Quote_cat::delete($this->data['cat_info']['id']);
    SystemMessages::add_success_message("התיקייה נמחקה");
    return $this->eject_redirect();
}  

  public function include_edit_view(){
    $this->add_cat_info_data();
    $this->include_view('quote_cats/edit.php');
  }

  public function include_add_view(){
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
      return $this->redirect_to(inner_url('quote_cats/list/'));
    }

    protected function after_edit_redirect($item_id){
      return $this->redirect_to(inner_url('quote_cats/list/'));
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