<?php
  class QuotesController extends CrudController{
    public $add_models = array("quotes","quote_cat");

    protected function init_setup($action){
        if($action != 'cat_list'){
            $cat_id = $this->add_cat_info_data();
            if(!$cat_id){
                return $this->redirect_to(inner_url("quotes/cat_list/"));
                return false;
            }
        }
        return parent::init_setup($action);
    }

    public function cat_list(){
        $payload = array(
            'order_by'=>'label'
        );
        $item_list = Quote_cat::get_list(array(),"*", $payload);
        $this->data['item_list'] = $this->prepare_forms_for_all_list($item_list,Quote_cat::setup_field_collection(),"quote_cat_");
    
        $this->include_view('quotes/cat_list.php');
    }

    public function create_catSend(){
        $fields_collection = Quote_cat::setup_field_collection();
        $form_handler = $this->init_form_handler("new_quote_cat");
        $form_handler->update_fields_collection($fields_collection);
        $validate_result = $form_handler->validate();
        if($validate_result['success']){
            $fixed_values = $validate_result['fixed_values'];
            $row_id = Quote_cat::create($fixed_values);
            SystemMessages::add_success_message("התיקייה נוצרה בהצלחה");
        }
        else{
            if(!empty($validate_result['err_messages'])){
                foreach($validate_result['err_messages'] as $err_message){
                    SystemMessages::add_err_message($err_message);
                }
            }
        }
        $this->eject_redirect();
    }

    public function update_catSend(){
        $cat_id = $_REQUEST['db_row_id'];

        $form_handler = $this->form_handlers["quote_cat_".$cat_id];
    
        $validate_result = $form_handler->validate();
        $fixed_values = $validate_result['fixed_values'];
        if($validate_result['success']){
            Quote_cat::update($cat_id,$fixed_values);
            SystemMessages::add_success_message("התיקייה עודכנה");
            
        }
        else{
            SystemMessages::add_err_message("שגיאה בעריכת התיקייה");
            if(!empty($validate_result['err_messages'])){
              foreach($validate_result['err_messages'] as $message){
                SystemMessages::add_err_message($message);
              }
            }
        }
        $this->eject_redirect();

    }


    public function delete_cat(){
        $fields_collection = Quote_cat::setup_field_collection(Quote_cat::get_fields_collection_for_cat_delete($this->data['cat_info']['id']));
        
        $form_handler = $this->init_form_handler("main");
        $form_handler->update_fields_collection($fields_collection);
        
        $this->add_form_builder_data($fields_collection,'delete_cat_confirm',$this->data['cat_info']['id']);  
        $this->include_view('quotes/cat_delete_form.php');
    }

    public function delete_cat_confirm(){
        $move_to_cat = $_REQUEST['row']['move_quotes_to'];
        Quote_cat::move_quotes_from_cat_to($this->data['cat_info']['id'],$move_to_cat);
        Quote_cat::delete($this->data['cat_info']['id']);
        SystemMessages::add_success_message("התיקייה נמחקה");
        return $this->eject_redirect();
    }    


    public function assign_cats(){
        $this->add_model("quote_cat_assign");
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
            Quote_cat_assign::assign_cats_to_item($this->data['item_info']['id'],  $assign_cats);      
            SystemMessages::add_success_message("התיקיות שוויכו בהצלחה");
            return $this->redirect_to(inner_url("quotes/assign_cats/?row_id=".$this->data['item_info']['id']."&cat_id=".$this->data['cat_info']['id']."")); 
        }

        $cat_list = Quote_cat::get_list(array(),"id, label");
        $cats_assigned = Quote_cat_assign::get_assigned_cats_to_item($this->data['item_info']['id']);
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
        $this->include_view('quotes/cat_assign_form.php',$info);
    }

    public function list(){
        //if(session__isset())
        
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'label'
        );
        $quote_list = Quotes::get_list($filter_arr,"*", $payload);      
        $this->data['quote_list'] = $quote_list;
        $this->include_view('quotes/list.php');
    }

    protected function add_cat_info_data(){

        if(!isset($_GET['cat_id'])){
            return false;
        }
        $cat_id = $_GET['cat_id'];
        $cat_info = Quote_cat::get_by_id($cat_id, 'id, label');
        $this->data['cat_info'] = $cat_info;
        if($cat_info && isset($cat_info['id'])){
            return $cat_info['id'];
        }
    }

    protected function get_base_filter(){
        $cat_id = $this->add_cat_info_data();
        if(!$cat_id){
            return;
        }

        $filter_arr = array(
            'cat_id'=>$cat_id,
    
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
        $this->include_view('quotes/edit.php');
    }

    public function include_add_view(){
        $this->include_view('quotes/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("הבלוק עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("הבלוק נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("הבלוק נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר בלוק");
    }   

    protected function delete_item($row_id){
      return Quotes::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Quotes::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('quotes/cat_list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("quotes/edit/?cat_id=".$this->data['cat_info']['id']."&row_id=".$item_info['id']);
    }

    protected function after_delete_redirect(){
        return $this->redirect_to(inner_url("/quotes/list/?cat_id=".$this->data['cat_info']['id']));
    }

    public function delete_url($item_info){
        return inner_url("quotes/delete/?cat_id=".$this->data['cat_info']['id']."&row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
        $fields_collection = Quotes::$fields_collection;
        if(isset($fields_collection['cat_id'])&& isset($this->data['cat_info'])){
            $fields_collection['cat_id']['default'] = $this->data['cat_info']['id'];
        }
        return Quotes::setup_field_collection($fields_collection);
    }

    protected function update_item($item_id,$update_values){
      return Quotes::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        return Quotes::create($fixed_values);
    }
  }
?>