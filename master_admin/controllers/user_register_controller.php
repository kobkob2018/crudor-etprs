<?php
	class User_registerController extends CrudController{
		public $add_models = array("masterUser_register");

        public function list(){
            $filter_arr = $this->get_base_filter();
            $register_list = MasterUser_register::get_list($filter_arr);  
            $info = array(
                'register_list'=>$register_list
            );
            $this->include_view('user_register/list.php',$info);
        }
    
        protected function get_base_filter(){
           
            $filter_arr = array(
               
            );  

            return $filter_arr;     
        }

	}
?>