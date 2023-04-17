<?php
//http://mylove.com/myleads/payments/lounch_fee/?row_id=9
	class Yaadpay_demoController extends CrudController{
		public $add_models = array();

        protected function handle_access($action){
            return true;
        }

        public function pay_pl(){
            $yaad_action = $_POST['action']."_return";

            $this->set_layout('blank');
            if($yaad_action == 'getToken_return'){
                return $this->getToken_return();
            }
            $return_method = 'ok';
            $CCode = 0;
            if($_REQUEST['Tash'] == '5'){
                $return_method = 'error';
                $CCode = rand(1,99999);
            }

            $user_id = '';
            if(isset($_REQUEST['UserId'])){
                $user_id = $_REQUEST['UserId'];
            }
            $result = array(
                'Order'=>$_REQUEST['Order'],
                'Id'=>rand(10000,99999),
                'CCode'=>$CCode,
                'Amount'=>$_REQUEST['Amount'],
                'ACode'=>rand(0,999),
                'Fild1'=>'שם הלקוח',
                'Fild2'=>'client@email.com',
                'Fild3'=>'08-3334343434',
                'Tmonth'=>'08',
                'Tyear'=>'2025',
                'Hesh'=>'1',
                'UserId'=>$user_id

            );
            return $this->$yaad_action($result,$return_method);
        }

        protected function pay_return($result,$return_method){

            //after pay with form(not token), add token data
            $result['L4digit'] = rand(1111,9999);

            $return_url = get_config('base_url')."/myleads/yaad_return/$return_method/?";
            $return_url .= $this->create_url_params($result);
            
            exit("for demo return, go to: ". $return_url);
            $this->redirect_to($return_url);
        }

        protected function soft_return($result,$return_method){
            $return_str = $this->create_url_params($result);
            print($return_str);
            exit();
        }

        protected function getToken_return($result,$return_method){
            print(rand(1111,9999));
            exit();
        }

        protected function create_url_params($result){
            $url_arr = array();
            foreach($result as $key=>$val){
                $url_arr[] = "$key=$val";
            }
            return implode("&",$url_arr);
        }

        public function get_demo_invoice(){
            $this->set_layout('blank');
            return $this->include_view('payments/yaad_invoice_demo.php');
        }
}	

?>