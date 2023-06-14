<?php
  class myleadsController extends CrudController{
    public $add_models = array("userLogin");

    public function quick_access(){
        $user_id = $_GET['user_id'];
        UserLogin::add_login_trace($user_id,false,'myleads_');
        $this->set_layout('blank');
        if(isset($_GET['lead_id'])){
          session__set('show_row',$_GET['lead_id'],'myleads_');
        }
        $this->redirect_to(inner_url('myleads/',array('system')));
    }
  }

?>