<?php
  class User_lounch_feeController extends CrudController{
    public $add_models = array("user_lounch_fee");

    protected function init_setup($action){
        return parent::init_setup($action);
    }

    protected function validate_user_id(){
        $user_id = $this->add_user_info_data();
        if(!$user_id){
            return $this->redirect_to(inner_url("users/list/"));
            return false;
        }
        return $user_id;
    }

    

    public function list(){
       // $user_id = $this->add_user_info_data();
        if(!$this->add_user_info_data()){
            return $this->all_users_pending_list();
        }

        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'id desc'
        );
        $user_fee_lounches = User_lounch_fee::get_list($filter_arr,"*",$payload);
        $this->data['fields_collection'] = User_lounch_fee::setup_list_field_collections($this->data['user_info']);
        $this->data['user_fee_lounches'] = $user_fee_lounches;
        $this->include_view('user_lounch_fee/list.php');
    }

    public function all_users_pending_list(){
        $filter_arr = array('pay_status'=>'0');
        $payload = array(
            'order_by'=>'id desc'
        );
        $user_fee_lounches = User_lounch_fee::get_list($filter_arr,"*",$payload);
        $fee_users = array();
        foreach($user_fee_lounches as $fee_key=>$user_fee){
            if(!isset($fee_users[$user_fee['user_id']])){
                $fee_users[$user_fee['user_id']] = Users::get_by_id($user_fee['user_id'],'id, full_name');
            }
            $fee_user = $fee_users[$user_fee['user_id']];
            $user_fee_lounches[$fee_key]['user'] = $fee_user;
        }
        $this->data['fields_collection'] = User_lounch_fee::setup_list_field_collections();
        $this->data['user_fee_lounches'] = $user_fee_lounches;
        $this->include_view('user_lounch_fee/all_users_pending_list.php');
    }   

    protected function add_user_info_data(){
        if(isset($this->data['user_info'])){
            return $this->data['user_info']['id'];
        }
        if(!isset($_GET['user_id'])){
            return false;
        }
        $user_id = $_GET['user_id']; 
        $user_info = Users::get_by_id($user_id, 'id, full_name, email');
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

    public function after_add_redirect($row_id){
        $return_url = inner_url("user_lounch_fee/list/?user_id=".$this->data['user_info']['id']); 
        return $this->redirect_to($return_url);
    }

    public function add(){
        if(!$this->validate_user_id()){
            return;
        }
        //override bcoz we need some special default values
        $fields_collection = User_lounch_fee::setup_add_field_collections($this->data['user_info']);
        $form_handler = $this->init_form_handler();
        $form_handler->update_fields_collection($fields_collection);
  
        $this->send_action_proceed();
        $this->add_form_builder_data($fields_collection,'createSend','new');
        $this->send_action_proceed();
        $this->include_add_view();
    }       

    public function createSend(){
        return parent::createSend();
    }

    public function delete(){
        $this->add_user_info_data();
        if(!isset($_GET['row_id'])){
            $this->row_error_message();
            return $this->eject_redirect();
        }
        $deleted_values = array('deleted'=>'1','pay_status'=>'5');
        User_lounch_fee::update($_GET['row_id'],$deleted_values);
        $this->delete_success_message();
        //return parent::delete();      
        return $this->eject_redirect();
    }

    public function include_add_view(){
        $this->include_view('user_lounch_fee/add.php');
    }   

    protected function create_success_message(){
        SystemMessages::add_success_message("החיוב נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("החיוב נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר חיוב");
    }   

    public function eject_url(){
        if($this->data['user_info']){
            return inner_url('user_lounch_fee/list/?user_id='.$this->data['user_info']['id']);
        }
        else{
            return inner_url('user_lounch_fee/list/');
        }
    }

    public function delete_url($item_info){
        return inner_url("user_lounch_fee/delete/?row_id=".$item_info['id']."&user_id=".$this->data['user_info']['id']);
    }

    protected function get_fields_collection(){
      return User_lounch_fee::setup_field_collection();
    }

    protected function create_item($fixed_values){
        $fixed_values['user_id'] = $this->data['user_info']['id'];
        $fixed_values['owner_id'] = $this->user['id'];
        $token = md5(time().rand(10000,99999));
        $fixed_values['token'] = $token;
        $row_id = User_lounch_fee::create($fixed_values);
        $info = $fixed_values;
        $info['row_id'] = $row_id;
        $info['full_name'] = $this->data['user_info']['full_name'];
        $email_to = $info['email_to_send'];
        $email_title = "בקשה לתשלום אוטומטי";
        $email_content = $this->include_ob_view('emails_send/user_lounch_fee.php',$info);
        
        $this->send_email($email_to, $email_title, $email_content);
        return $row_id;
    }
  }
?>