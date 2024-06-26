<?php
  class QuotesController extends CrudController{
    public $add_models = array("quotes","quote_cat","quotes_user");

    protected function handle_access($action){
        switch ($action){
          case 'my_list':
            return $this->call_module('admin','handle_access_user_can','quotes');
            break;
          default:
            return parent::handle_access(($action));
            break;
          
        }
    }

    protected function init_setup($action){
        $this->add_cat_info_data();
        return parent::init_setup($action);
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

    public function my_list(){
        $info = array('list_user'=>$this->user);
        return $this->setup_user_list($this->user['id'],$info);
    }

    public function assign_queue_to_user(){
        if(!session__isset('quots_list_user')){
            return $this->redirect_to(inner_url("quote_cats/list/"));
        }
        $session_param = 'quotes_queue';
        if(!session__isset($session_param)){
            return $this->redirect_to(inner_url("quotes/user_list/"));
        }
        $quotes_queue = session__get($session_param);
        if(!is_array($quotes_queue)){
            return $this->redirect_to(inner_url("quotes/user_list/"));
        }
        $user_id = session__get('quots_list_user');
        foreach($quotes_queue as $quote_id){
            Quotes::update($quote_id, array('user_id'=>$user_id));   
        }
        session__unset($session_param);
        return $this->redirect_to(inner_url("quotes/user_list/"));
    }

    public function user_list(){
        
        if(isset($_REQUEST['user_id'])){
            session__set('quots_list_user',$_REQUEST['user_id']);
            return $this->redirect_to(inner_url("quotes/user_list/"));
        }
        if(!session__isset('quots_list_user')){
            return $this->redirect_to(inner_url("quote_cats/list/"));
        }
        $this->add_model("quote_cat_assign");
        $user_id = session__get('quots_list_user');
        $user = Users::get_by_id($user_id);
        $this->data['user_info'] = $user;
        $info = array('list_user'=>$user);
        return $this->setup_user_list($user_id,$info);
    }

    protected function setup_user_list($user_id, $info){
        $this->add_model("quote_cat_assign");
        $this->add_model("user_quote_cat_enable");

        $quote_cat_list_arr = Quote_cat::get_list(array('deleted'=>'0'));
        $quote_cat_by_id = Helper::eazy_index_arr_by('id',$quote_cat_list_arr);
        $filter_arr = array('user_id'=>$user_id);
        $user_quote_cat_enable = User_quote_cat_enable::get_list($filter_arr,'cat_id');
        $user_cat_list_arr = array();
        foreach($user_quote_cat_enable as $cat){
            $user_cat_list_arr[] = $quote_cat_by_id[$cat['cat_id']];
        }
        $info['user_cat_list_arr'] = $user_cat_list_arr;
        $payload = array(
            'order_by'=>'label'
        );
        $quote_list = Quotes::get_list($filter_arr,"*", $payload);
        foreach($quote_list as $quote_id=>$quote){
            $quote_cat_assign = Quote_cat_assign::get_list(array('quote_id'=>$quote['id']),'cat_id');
            $quote_cats = array();
            foreach($quote_cat_assign as $cat){
                $quote_cats[$cat['cat_id']] = array('id'=>$cat['cat_id'],'label'=>$quote_cat_by_id[$cat['cat_id']]['label']);
            }
            $quote_list[$quote_id]['cats_assigned'] = $quote_cats;
        }
        $this->data['quote_list'] = $this->prepare_forms_for_all_list($quote_list);
        //for the add item form
        $form_handler = $this->init_form_handler();
        $form_handler->update_fields_collection($this->get_fields_collection());
        $this->include_view('quotes/user_list.php',$info);
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

    public function reset_queue(){
        if(session__isset('quotes_queue')){
            session__unset('quotes_queue');
        }
        SystemMessages::add_success_message("תור הצעות מחיר לשיוך ללקוח הוסר");
        return $this->redirect_to(inner_url('quote_cats/list/'));
    }

    public function enter_queue(){
        $return_action = 'list';
        if(isset($_REQUEST['return_to'])){
            $return_action = $_REQUEST['return_to'];
        }
        $qstr = "";
        if(isset($_REQUEST['cat_id'])){
            $qstr = "?cat_id=".$_REQUEST['cat_id'];
        }
        $return_url = inner_url("quotes/".$return_action."/".$qstr);
        $session_queue = array();
        $session_param = 'quotes_queue';
        if(session__isset($session_param)){
            $session_queue = session__get($session_param);
        }
        if(!isset($_REQUEST['row_id'])){
            SystemMessages::add_err_message("לא נבחרה הצעת מחיר");
            return $this->redirect_to($return_url);
        }
        $row_id = $_REQUEST['row_id'];
        if($row_id == 'all'){
            if(!isset($_REQUEST['cat_id'])){
                SystemMessages::add_err_message("לא נבחרה תיקייה");
                return $this->redirect_to($return_url);
            }
            $cat_id = $_REQUEST['cat_id'];
            $cat_quotes = Quotes::simple_get_list_by_table_name(array('cat_id'=>$cat_id),'quote_cat_assign');
            if($cat_quotes){
                foreach($cat_quotes as $quote){
                    $check_quote = Quotes::get_by_id($quote['quote_id']);
                    
                    if($check_quote && !in_array($quote['quote_id'],$session_queue)){
                        $session_queue[] = $quote['quote_id'];
                    } 
                }
                SystemMessages::add_success_message("הצעות המחיר בתיקייה נוספו לתור");
                
            }
        }
        else{
            if(!in_array($row_id,$session_queue)){
                $session_queue[] = $row_id;
            }  

            SystemMessages::add_success_message("הצעת המחיר נוספה לתור");
        }
        session__set($session_param,$session_queue);
        SystemMessages::add_success_message("יש לגשת לדף לקוח וללחוץ על כפתור - שיוך הצעות מחיר שבתור ללקוח זה");

        return $this->redirect_to($return_url);
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
        SystemMessages::add_success_message("הצעת המחיר עודכנה בהצלחה");
    }

    protected function create_success_message(){
        SystemMessages::add_success_message("הצעת המחיר נוצרה בהצלחה");
    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("הצעת המחיר נמחקה");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחרה הצעת מחיר");
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
        $list_action = 'list';
        if(isset($_REQUEST['list_action'])){
            $list_action = $_REQUEST['list_action'];
        }
        $url = inner_url("/quotes/$list_action/");
        if(isset($this->data['cat_info'])){
            $url.= "?cat_id=".$this->data['cat_info']['id']; 
        }
        return $this->redirect_to($url);
    }

    public function redirect_back_to_item($item_info){
        if(isset($_REQUEST['user_id'])){
            $user_id = $_REQUEST['user_id'];
            return $this->redirect_to(inner_url("quotes/user_list/?user_id=$user_id"));
        }
        return parent::redirect_back_to_item($item_info);
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
        Quotes::update($item_id,$update_values);

        if(isset($_REQUEST['assign'])){ 
            $this->add_model("quote_cat_assign");
            $assign_cats = array();
            foreach($_REQUEST['assign'] as $cat){
                if($cat != '-1'){
                    $assign_cats[] = $cat;
                }
            }   
            Quote_cat_assign::assign_cats_to_item($item_id, $assign_cats);   
        }
          
    }

    protected function create_item($fixed_values){ 
        $fixed_values['status'] = '1';
        if(isset($_REQUEST['row']['user_id'])){
            $user_id = $_REQUEST['row']['user_id'];
            $fixed_values['user_id'] = $user_id;
            //automaticlly set the quote status by the user settings
            $quotes_user = Quotes_user::find(array('user_id'=>$user_id));
            if(!$quotes_user){
                //untill no settings created for the user, his quotes are suspended
                $fixed_values['status'] = '9';
            }
            elseif($quotes_user['status'] == '0'){
                $fixed_values['status'] = '9';
            }
        }
        $item_id = Quotes::create($fixed_values);
        if(isset($fixed_values['cat_id']) && $fixed_values['cat_id'] != '0'){
            $this->add_model("quote_cat_assign"); 
            Quote_cat_assign::add_item_to_cat($item_id,$fixed_values['cat_id']);
        }
        if(isset($_REQUEST['assign'])){ 
            $this->add_model("quote_cat_assign");
            $assign_cats = array();
            foreach($_REQUEST['assign'] as $cat){
                if($cat != '-1'){
                    $assign_cats[] = $cat;
                }
            }   
            Quote_cat_assign::assign_cats_to_item($item_id, $assign_cats);   
        }
        return $item_id;
    }
  }
?>