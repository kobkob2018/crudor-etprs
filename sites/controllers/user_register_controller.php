<?php
  class User_registerController extends CrudController{
    public $add_models = array("user_register");

    public function email_confirm(){
      if(!isset($_REQUEST['token']) || !isset($_REQUEST['row_id'])){
        return $this->redirect_to(inner_url("user_register/add/"));
      }
      $row_id = $_REQUEST['row_id'];
      $token = $_REQUEST['token'];
      $filter_arr = array(
        'id'=>$row_id,
        'token'=>$token
      );
      $user_register = User_register::find($filter_arr);
      if(!$user_register){
        echo __tr("Email token expired");
        return;
      }
      User_register::update($row_id,array("token"=>'','status'=>'email_confirmed'));
      return $this->include_view("user_register/register_success.php");
    }

    public function email_notifocation(){
      if(!session__isset('user_register_row')){
        return $this->redirect_to(inner_url("user_register/add"));
      }
      $row_id = session__get('user_register_row');
      $user_register = User_register::get_by_id($row_id);
      $info = $user_register;
      $info['site'] = $this->data['site'];

      return $this->include_view("user_register/email_notification_message.php",$info);
    }

    public function add(){
        return parent::add();
    }       

    public function createSend(){
        return parent::createSend();
    }

    public function include_add_view(){
        $this->include_view('user_register/add.php');
    }   

    protected function create_success_message(){
        SystemMessages::add_success_message(__tr("Register success"));

    }

    protected function get_item_info($row_id){
      return User_register::get_by_id($row_id);
    }

    protected function get_fields_collection(){
        return User_register::setup_field_collection();
    }

    protected function create_item($fixed_values){
      $token = md5(time().rand(10000,99999));
      $fixed_values['token'] = $token;
      $filter_arr = array('email'=>$fixed_values['email']);
      $user_row = User_register::find($filter_arr,'id');
      if($user_row){
        $row_id = $user_row['id'];
        User_register::update($row_id,$fixed_values);
      }
      else{
        $row_id = User_register::create($fixed_values);
      }
      $email_info = $fixed_values;
      $email_info['row_id'] = $row_id;
      $email_info['confirm_url'] = outer_url("user_register/email_confirm/?token=".$token."&row_id=".$row_id);
      $email_info['site'] = $this->data['site'];
      
      $email_content = $this->include_ob_view("user_register/email_confirm_notification.php",$email_info);

      $this->send_email($fixed_values['email'],__tr("Register email confirmation"),$email_content);

      return $row_id;
    }

    public function after_add_redirect($row_id){
      session__set("user_register_row",$row_id);
      return $this->redirect_to(inner_url("user_register/email_notifocation"));
    }

    public function validate_by_email($value, $validate_payload){
      $return_array =  array(
        'success'=>true
      );

      $filter_arr = array('email'=>$value);
      $user_found = Users::find($filter_arr);
      if($user_found){
          $return_array['success'] = false;
          $return_array['message'] = __tr("A user with this email already exists");
      }
      return $return_array;
    }

    public function validate_by_password($value, $validate_payload){

        global $action;

        $return_array =  array(
            'success'=>true
        );

        if($value == '' && $action == 'add'){
            $return_array['success'] = false;
            $return_array['message'] = __tr("Please select a stronger password");
            return $return_array;
        }

        if($value == '' && $action == 'edit'){
            $return_array['fixed_value'] = $this->data['item_info'][$validate_payload['key']];
            return $return_array;
            
        }

        $password_confirm = $_REQUEST['row'][$validate_payload['key'].'_confirm'];

        if($value != $password_confirm){
            $return_array['success'] = false;
            $return_array['message'] = __tr("Passwords not match");
            return $return_array;
        }

        if(strlen($value) < 6 ){
            $return_array['success'] = false;
            $return_array['message'] = __tr("Please select a stronger password");
            return $return_array;
        }

        $return_array['fixed_value'] = md5($value);



        return $return_array;
    }
  }
?>