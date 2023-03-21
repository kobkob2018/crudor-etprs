<?php
  class User_phonesController extends CrudController{
    public $add_models = array("user_phones","users");

    protected function init_setup($action){
        $this->data['add_leads_menu'] = true;
        $user_id = $this->add_user_info_data();
        if(!$user_id){
            return $this->redirect_to(inner_url("users/list/"));
            return false;
        }
        return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $user_phones = User_phones::get_list($filter_arr,"*");
        $this->data['fields_collection'] = User_phones::setup_field_collection();
        $this->data['user_phones'] = $user_phones;
        $this->include_view('user_phones/list.php');
    }

    protected function add_user_info_data(){
        if(isset($this->data['user_info'])){
            return $this->data['user_info']['id'];
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

    protected function add_phone_info_data(){
        if(isset($this->data['phone_info'])){
            return $this->data['phone_info']['id'];
        }
        if(isset($this->data['item_info'])){
            $this->data['phone_info'] = $this->data['item_info'];
            return $this->data['phone_info']['id'];
        }
        $phone_id = false;
        if(isset($_GET['row_id'])){
            $phone_id = $_GET['row_id'];
        }
        if(isset($_GET['phone_id'])){
            $phone_id = $_GET['phone_id'];
        }
        if(!$phone_id){
            return false;
        }
        
        $phone_info = User_phones::get_by_id($phone_id, 'id, number');
        $this->data['phone_info'] = $phone_info;
        if($phone_info && isset($phone_info['id'])){
            return $phone_info['id'];
        }
    }

    protected function get_base_filter(){
        $user_id = $this->add_user_info_data();
        if(!$user_id){
            return;
        }

        $filter_arr = array(
            'user_id'=>$user_id,
        );  
        return $filter_arr;     
    }


    public function edit_api_list(){
        $user_id = $this->add_user_info_data();
        $phone_id = $this->add_phone_info_data();
        $filter_arr = $this->get_base_filter();
        $filter_arr['phone_id'] = $phone_id;
        $this->add_model('user_phone_api');
        $api_list = User_phone_api::get_list($filter_arr);

        
        $this->data['api_list'] = $this->prepare_forms_for_all_list($api_list);

        $this->include_view('user_phones/api_list.php');
    }

    public function updateApiSend(){
        
        if(!isset($_REQUEST['row'])){
            return;
        }
        if($_REQUEST['row']['url'] == ""){
            SystemMessages::add_err_message("הAPI שדה חובה");
            return $this->redirect_to_api_list();
        }
            
        $this->add_model('user_phone_api');
        $fixed_values = array(
            'user_id'=>$this->data['user_info']['id'],
            'phone_id'=>$this->data['phone_info']['id'],
            'url'=>$_REQUEST['row']['url']
        );
        $row_id = $_REQUEST['api_id'];
        if($row_id == "new"){
            User_phone_api::create($fixed_values);
        }
        else{
            User_phone_api::update($row_id,$fixed_values);
        }
        SystemMessages::add_success_message("הAPI עודכן בהצלחה");
        $this->redirect_to_api_list();

    }
    public function delete_api(){
        $user_id = $this->add_user_info_data();
        $phone_id = $this->add_phone_info_data();
        $row_id = $_REQUEST['api_id'];
        
        $this->add_model('user_phone_api');
        User_phone_api::delete($row_id);
        SystemMessages::add_success_message("הAPI נמחק בהצלחה");
        $this->redirect_to_api_list();
    }    
    public function createApiSend(){
        return $this->updateApiSend();
    }
    public function apiListUpdateSend(){
        return $this->updateApiSend();        
    }

    public function url_to_api_list(){
        return inner_url("user_phones/edit_api_list/?user_id=".$this->data['user_info']['id']."&phone_id=".$this->data['phone_info']['id']); 
    }

    public function redirect_to_api_list(){
        return $this->redirect_to($this->url_to_api_list());
    }

    public function after_add_redirect($row_id){
        $return_url = inner_url("user_phones/list/?user_id=".$this->data['user_info']['id']); 
        return $this->redirect_to($return_url);
    }

    public function after_edit_redirect($item_info){
        $return_url = inner_url("user_phones/list/?user_id=".$this->data['user_info']['id']); 
        return $this->redirect_to($return_url);       
    }

    public function edit(){
        $this->add_phone_info_data();
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

    public function set_priority(){
        return parent::set_priority();
    }

    public function include_edit_view(){
        $this->include_view('user_phones/edit.php');
    }

    public function include_add_view(){
        $this->include_view('user_phones/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("המספר עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("המספר נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("המספר נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר מספר");
    }   

    protected function delete_item($row_id){
      return User_phones::delete($row_id);
    }

    protected function get_item_info($row_id){
      return User_phones::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('user_phones/list/?user_id='.$this->data['user_info']['id']);
    }

    public function url_back_to_item($item_info){
      return inner_url("user_phones/edit/?row_id=".$item_info['id']."&user_id=".$this->data['user_info']['id']);
    }

    public function delete_url($item_info){
        return inner_url("user_phones/delete/?row_id=".$item_info['id']."&user_id=".$this->data['user_info']['id']);
    }

    protected function get_fields_collection(){
      return User_phones::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return User_phones::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        $fixed_values['user_id'] = $this->data['user_info']['id'];
        return User_phones::create($fixed_values);
    }
  }
?>