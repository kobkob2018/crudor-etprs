<?php
  class User_lead_apiController extends CrudController{
    public $add_models = array("users");

    protected function init_setup($action){
        $this->data['add_leads_menu'] = true;
        $user_id = $this->add_user_info_data();
        if(!$user_id){
            return $this->redirect_to(inner_url("users/list/"));
            return false;
        }
        return parent::init_setup($action);
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
        $filter_arr = $this->get_base_filter();
        
        $this->add_model('masterUser_lead_api');
        $api_list = MasterUser_lead_api::get_list($filter_arr);

        
        $this->data['api_list'] = $this->prepare_forms_for_all_list($api_list);

        $this->include_view('user_lead_api/api_list.php');
    }

    public function updateApiSend(){
        
        if(!isset($_REQUEST['row'])){
            return;
        }
        if($_REQUEST['row']['url'] == ""){
            SystemMessages::add_err_message("הAPI שדה חובה");
            return $this->redirect_to_api_list();
        }
            
        $this->add_model('masterUser_lead_api');
        $fixed_values = array(
            'user_id'=>$this->data['user_info']['id'],
            'url'=>$_REQUEST['row']['url']
        );
        $row_id = $_REQUEST['api_id'];
        if($row_id == "new"){
            MasterUser_lead_api::create($fixed_values);
        }
        else{
            MasterUser_lead_api::update($row_id,$fixed_values);
        }
        SystemMessages::add_success_message("הAPI עודכן בהצלחה");
        $this->redirect_to_api_list();

    }
    public function delete_api(){
        $user_id = $this->add_user_info_data();
        $row_id = $_REQUEST['api_id'];
        
        $this->add_model('masterUser_lead_api');
        MasterUser_lead_api::delete($row_id);
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
        return inner_url("user_lead_api/edit_api_list/?user_id=".$this->data['user_info']['id']."&phone_id=".$this->data['phone_info']['id']); 
    }

    public function redirect_to_api_list(){
        return $this->redirect_to($this->url_to_api_list());
    }
  }
?>