<?php
  class Refund_reasonsController extends CrudController{
    public $add_models = array("refund_reasons","users", "biz_categories");

    protected function init_setup($action){
        $this->data['add_leads_menu'] = true;
        $user_id = $this->add_user_info_data();
        $cat_id = $this->add_cat_info_data();
        return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        
        $refund_reasons = Refund_reasons::get_list($filter_arr,"*");
        $this->data['fields_collection'] = Refund_reasons::setup_field_collection();
        $this->data['refund_reasons'] = $refund_reasons;
        $this->include_view('refund_reasons/list.php');
    }

    protected function add_user_info_data(){
        if(isset($this->data['user_info'])){
            if($this->data['user_info']){
                return $this->data['user_info']['id'];
            }
            return false;
        }
        if(!isset($_GET['user_id'])){
            return false;
        }
        $user_id = $_GET['user_id']; 
        $user_info = Users::get_by_id($user_id, 'id, full_name');
        $this->data['user_info'] = $user_info;
        if($user_info && isset($user_info['id'])){
            return $user_info['id'];
        }
    }

    protected function add_cat_info_data(){
        if(isset($this->data['cat_info'])){
            if($this->data['cat_info']){
                return $this->data['cat_info']['id'];
            }
            return false;
        }
        if(!isset($_GET['cat_id'])){
            return false;
        }
        $cat_id = $_GET['cat_id']; 
        $cat_info = Biz_categories::get_by_id($cat_id, 'id, label');
        $this->data['cat_info'] = $cat_info;
        if($cat_info && isset($cat_info['id'])){
            return $cat_info['id'];
        }
    }

    protected function get_base_filter(){
        $filter_arr = array();
        $user_id = $this->add_user_info_data();
        $cat_id = $this->add_cat_info_data();
        if(!$user_id){
            $user_id = null;
        }
        if(!$cat_id){
            $cat_id = null;
        }

        $filter_arr['user_id'] = $user_id;
        $filter_arr['cat_id'] = $cat_id;
        return $filter_arr;     
    }

    public function add_heading_url_params(){
        if(isset($this->data['user_info']) && $this->data['user_info']){
            return "&user_id=".$this->data['user_info']['id'];
        }


        if(isset($this->data['cat_info']) && $this->data['cat_info']){
            return "&cat_id=".$this->data['cat_info']['id'];
        }
        return "";
    }

    public function include_edit_view(){
        $this->include_view('refund_reasons/edit.php');
    }

    public function include_add_view(){
        $this->include_view('refund_reasons/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("הסיבה עודכנה בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("הסיבה נוצרה בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("ההסיבה נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחרה סיבה");
    }   

    protected function delete_item($row_id){
      return Refund_reasons::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Refund_reasons::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url("refund_reasons/list/?n=1".$this->add_heading_url_params());
    }

    public function url_back_to_item($item_info){
      return inner_url("refund_reasons/edit/?row_id=".$item_info['id'].$this->add_heading_url_params());
    }

    public function after_add_redirect($row_id){
        $return_url = inner_url("refund_reasons/list/?n=1".$this->add_heading_url_params()); 
        return $this->redirect_to($return_url);
    }

    public function after_edit_redirect($item_info){
        $return_url = inner_url("refund_reasons/list/?n=1".$this->add_heading_url_params()); 
        return $this->redirect_to($return_url);       
    }

    public function delete_url($item_info){
        return inner_url("refund_reasons/delete/?row_id=".$item_info['id'].$this->add_heading_url_params());
    }

    protected function get_fields_collection(){
      return Refund_reasons::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Refund_reasons::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        if(isset($this->data['user_info']) && $this->data['user_info']){

            $fixed_values['user_id'] = $this->data['user_info']['id'];
        }
        if(isset($this->data['cat_info']) && $this->data['cat_info']){

            $fixed_values['cat_id'] = $this->data['cat_info']['id'];
        }
        return Refund_reasons::create($fixed_values);
    }
  }
?>