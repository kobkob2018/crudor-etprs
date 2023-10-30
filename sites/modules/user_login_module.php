<?php
	class User_loginModule extends Module{
        
        public function reg_pretty(){
            $this->controller->add_model("user_register");
            $info = array(
                'state'=>'reg_form'
            );
            if(isset($_REQUEST['reg'])){
                $info['state'] = 'check';
            }
            $this->include_view('user_login/register_pretty.php',$info);
        }

	}
?>