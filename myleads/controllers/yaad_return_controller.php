<?php
//http://mylove.com/myleads/payments/lounch_fee/?row_id=9
	class Yaad_returnController extends CrudController{
		public $add_models = array('myleads_lounch_fee','user_cc_token','myleads_pay_by_cc_log');
		
        protected $cc_log = null;
        protected $cc_log_set = false;
        public function ok(){
            $cc_log = $this->get_cc_log();

            if(!$cc_log){
                SystemMessages::add_err_message("אירעה שגיאה בתשלום");
                return $this->redirect_to(inner_url());
            }
            
            $this->update_cc_log_from_request($cc_log,'2');
            $this->add_user_cc_token($cc_log);
            print_r_help($cc_log);
            
            return $this->init_handler_module($cc_log,'ok');
			
            //update ilbizPayByCCLog 
            // insert user token
            // go to module for handle 
		}
        
        public function error(){
            $cc_log = $this->get_cc_log();
            if(!$cc_log){
                SystemMessages::add_err_message("אירעה שגיאה בתשלום");
                return $this->redirect_to(inner_url());
            }
            $this->update_cc_log_from_request($cc_log,'2');
            return $this->init_handler_module($cc_log,'error');
			
            //update ilbizPayByCCLog
            //init module function for returning to correct page
		}

        protected function init_handler_module($cc_log,$success = 'error'){
           exit("herreeee");
            if($cc_log['handle_module'] != '' && $cc_log['handle_method'] != ''){

                $action_data = array(
                    'cc_log'=>$cc_log,
                    'success'=>$success
                );
                exit("before init");
                return $this->call_module($cc_log['handle_module'],$cc_log['handle_method'],$action_data);
            }
            if($success == 'ok'){
              //  SystemMessages::add_success_message($cc_log['details']." - התשלום בוצע בהצלחה");
            }
            else{
               // SystemMessages::add_err_message($cc_log['details']." - אירעה שגיאה בעת התשלום, אנא נסה שוב.");
            }
        }

        protected function get_cc_log(){
            if($this->cc_log_set){
                return $this->cc_log;
            }
            $this->cc_log_set = true;
            if(!isset($_REQUEST['Order'])){
                return $this->cc_log;
            }
            $this->cc_log = Myleads_pay_by_cc_log::get_by_id($_REQUEST['Order']);
            $this->cc_log_set = true;
            return $this->cc_log;
        }

        protected function update_cc_log_from_request($cc_log,$pay_good = '1'){
            $update_arr = array(
                'pay_good'=>$pay_good,
                'trans_id'=>$_REQUEST['Id'],
                'CCode'=>$_REQUEST['CCode'],
                'Amount_paid'=>$_REQUEST['Amount'],
                'ACode'=>$_REQUEST['ACode']
            );
            
            Myleads_pay_by_cc_log::update($cc_log['id'],$update_arr);
        }        

        protected function add_user_cc_token($cc_log){
            if(!(isset($_REQUEST['L4digit']) && $_REQUEST['L4digit']!='' && $_REQUEST['L4digit']!= '0')){
                return;
            }
            //check if token not allready exist
            $filter_arr = array(
                'user_id'=>$cc_log['user_id'],
                'L4digit'=>$_REQUEST['L4digit']
            );
            $tokens_exist = User_cc_token::get_list($filter_arr);
            if($tokens_exist){
                return;
            }
            $yaad_token = $this->get_token_from_yaad($_REQUEST['Id']);
            $new_token = array(
                'user_id'=>$cc_log['user_id'],
                'transaction_id'=>$_REQUEST['Id'],
                'token'=>$yaad_token,
                'L4digit'=>$_REQUEST['L4digit'],
                'Tmonth'=>$_REQUEST['Tmonth'],
                'Tyear'=>$_REQUEST['Tyear'],
                'customer_ID_number'=>$_REQUEST['UserId'],
                'Fild1'=>$_REQUEST['Fild1'],
                'Fild2'=>$_REQUEST['Fild2'],
                'Fild3'=>$_REQUEST['Fild3'],
                'full_name'=>$cc_log['full_name'],
                'biz_name'=>$cc_log['biz_name']
            );
            return User_cc_token::create($new_token);
        }

        protected function get_token_from_yaad($transaction_id){
	
            $params = array(
                'Pass'=>get_config('yaad_api_token_pass'),
                'action'=>'getToken',
                'Masof'=>get_config('yaad_api_masof'),
                'TransId'=>$transaction_id,
                'allowFalse'=>'True',
            );
          $postData = '';
           //create name value pairs seperated by &
           foreach($params as $k => $v) 
           { 
              $postData .= $k . '='.$v.'&'; 
           }
           $postData = rtrim($postData, '&');
         
            $ch = curl_init();  
            $api_url = get_config('yaad_api_url');
            curl_setopt($ch,CURLOPT_URL,$api_url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_HEADER, false); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
         
            $output=curl_exec($ch);
         
            curl_close($ch);
            
            $result_arr = explode("&",$output);
            $result = array();
            foreach($result_arr as $result_val){
                $val_arr = explode("=",$result_val);
                if(isset($val_arr[0]) && isset($val_arr[1])){
                    $result[$val_arr[0]] = $val_arr[1];
                }
            }
            if(isset($result['Token'])){
                return $result['Token'];
            }
            return false;
        }


}	

?>