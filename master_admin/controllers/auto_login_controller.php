<?php
  class auto_loginController extends CrudController{
    public $add_models = array("userLogin");

    public function myleads(){
      $system_name = 'myleads';
      if(isset($_GET['lead_id'])){
        session__set('show_row',$_GET['lead_id'],$system_name.'_');
      }
      return $this->quick_access($system_name);
    }

    public function admin(){
      $system_name = 'admin';
      session__unset('workon_site',$system_name.'_');
      return $this->quick_access($system_name);
    }

    public function quick_access($system_name){
      $user_id = $_GET['user_id'];
      UserLogin::add_login_trace($user_id,false,$system_name.'_');
      $this->set_layout('blank');

      $this->redirect_to(inner_url($system_name.'/',array('system')));
  }
  }

?>