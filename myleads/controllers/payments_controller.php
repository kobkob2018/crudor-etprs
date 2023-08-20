<?php

  class PaymentsController extends CrudController{
    public $add_models = array('myleads_lounch_fee','user_cc_token','cities','myleads_pay_by_cc_log','myleads_old_user_payments');
    

    protected function handle_access($action){
        if($action == 'lounch_fee_token'){
            return true;
        }
		return parent::handle_access($action);
	}
 
    public function list(){
        $filter_arr = array(
            'user_id'=>$this->user['id'],
            'pay_good'=>'2'
        );
        $pay_log_list = Myleads_pay_by_cc_log::get_list($filter_arr,"*, DATE_FORMAT(pay_date,'%d-%m-%Y') as pay_date_heb");
    
        $this->data['pay_log_list'] = $pay_log_list;


        $old_user_payments = Myleads_old_user_payments::get_list($filter_arr,"*, DATE_FORMAT(pay_date,'%d-%m-%Y') as pay_date_heb");
        
        if($old_user_payments && !empty($old_user_payments)){
            $this->data['old_user_payments'] = $old_user_payments;
        }
        else{
            $this->data['old_user_payments'] = false;
        }
        
        return $this->include_view('payments/list.php');
    }

    public function lounch_fee_list(){
        $filter_arr = array(
            'user_id'=>$this->user['id'],
            'pay_status'=>'0'
        );
        $fee_list = Myleads_lounch_fee::get_list($filter_arr,"*, DATE_FORMAT(until_date,'%d-%m-%Y') as until_date_heb");
        
        if($fee_list){
            foreach($fee_list as $fee_key=>$fee){
                $days_diff = $this->get_days_to($fee['until_date']);
                $fee['days_left_to_pay'] = $days_diff;
                $fee_list[$fee_key] = $fee;
            }
        }
        $this->data['fee_list'] = $fee_list;
        return $this->include_view('payments/lounch_fee_list.php');
    }

    protected function get_days_to($date){
        $start = strtotime(date("Y-m-d"));
        $end = strtotime($date);
        $days_between = ceil(abs($end - $start) / 86400);
        return $days_between;
    }

    public function eject_url(){
        return inner_url('payments/lounch_fee_list/');
    }

    public function lounch_fee(){
        if(!isset($_REQUEST['row_id'])){
            return $this->redirect_to(inner_url());
        }
        
        $lounch_fee = Myleads_lounch_fee::get_by_id($_REQUEST['row_id']);

        if($lounch_fee['user_id'] != $this->user['id']){
            SystemMessages::add_err_message('אירעה שגיאה, חיוב לא קיים');
            return $this->eject_redirect();
        }

        if(!$lounch_fee){
            SystemMessages::add_err_message("לא נמצאה הבקשה לתשלום");
            return $this->eject_redirect();
        }

        if($lounch_fee['deleted'] == '1'){
            SystemMessages::add_err_message("הבקשה לתשלום בוטלה");
            return $this->eject_redirect();
        }

        if($lounch_fee['pay_status'] == '1'){
            SystemMessages::add_success_message("התשלום לבקשה זו כבר בוצע");
            return $this->eject_redirect();
        }
    
        $user_cc_tokens = User_cc_token::get_list(array('user_id'=>$this->user['id']));
        $info = array('user_cc_tokens'=>$user_cc_tokens,'lounch_fee'=>$lounch_fee);
        return $this->include_view('payments/lounch_fee_pay.php',$info);
    }

    public function get_invoice(){
        if(!isset($_REQUEST['row_id'])){
            return $this->eject_redirect();
        }

        if(isset($_REQUEST['masof_version']) && $_REQUEST['masof_version'] == 'old'){
            $cc_log = Myleads_old_user_payments::get_by_id($_REQUEST['row_id']);
        
            $yaad_user = get_config('yaad_invoice_user_old');
            $yaad_pass = get_config('yaad_invoice_pass_old');
            $yaad_masof = get_config('yaad_invoice_masof_old');
            $yaad_url = get_config('yaad_invoice_url_old');  
        }

        else{   
            $cc_log = Myleads_pay_by_cc_log::get_by_id($_REQUEST['row_id']);
           
            $yaad_user = get_config('yaad_invoice_user');
            $yaad_pass = get_config('yaad_invoice_pass');
            $yaad_masof = get_config('yaad_invoice_masof');
            $yaad_url = get_config('yaad_invoice_url');
        }

        if((!$cc_log) || $cc_log['user_id'] != $this->user['id'] || $cc_log['trans_id'] == ''){
            return $this->eject_redirect();
        }

        $trans_id = $cc_log['trans_id'];
        
        $postData = "d=s&action=PrintHesh&TransId=$trans_id&type=HTML&Masof=$yaad_masof&User=$yaad_user&Pass=$yaad_pass&HeshORCopy=True";

        $ch = curl_init();  
         
        curl_setopt($ch,CURLOPT_URL,$yaad_url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
        
        $output=curl_exec($ch);
        
        $output = str_replace("<img","<img style='display:none;' ",$output);
        $output = iconv ('windows-1255', 'utf8', $output);
        curl_close($ch);	
        return $this->include_view('payments/yaad_invoice.php',$output);
    }

    public function send_to_yaad(){
        if(!isset($_REQUEST['row_id'])){
            return $this->redirect_to(inner_url());
        }

        $lounch_fee = Myleads_lounch_fee::get_by_id($_REQUEST['row_id']);

        $pay_by_cc_log_data = array(
            'sum_total'=>$lounch_fee['price'],
            'details'=>$lounch_fee['details'],
            'user_id'=>$this->user['id'],
            'handle_module'=>'user_lounch_fee',
            'handle_method'=>'submit_pay',
            'lounch_id'=>$_REQUEST['row_id']
        );

        $pay_by_cc_log_id = Myleads_pay_by_cc_log::create($pay_by_cc_log_data);
        
        $update_lounch_data = array('order_id'=>$pay_by_cc_log_id);

        Myleads_lounch_fee::update($_REQUEST['row_id'],$update_lounch_data);

        $details = str_replace('"' , "''" , stripslashes($lounch_fee['details']));
        $yaad_api_url = get_config('yaad_api_url');
        if($_REQUEST['use_token']!='0'){

            $user_token_arr = User_cc_token::get_list(array('user_id'=>$this->user['id'],'L4digit'=>$_REQUEST['use_token']));

            if((!$user_token_arr) || (!isset($user_token_arr[0]))){
                SystemMessages::add_err_message("אירעה שגיאה בחיוב");
                return $this->eject_redirect();
            }

            $user_token_data = $user_token_arr[0];
					
            $params = array(
                'Masof'=>get_config('yaad_api_masof'),
                'action'=>'soft',
                'PassP'=>get_config('yaad_api_pass'),
                'Token'=>'True',
                'Order'=>$pay_by_cc_log_id,
                'Amount'=>$lounch_fee['price'],
                'Info'=>$details,
                'UserId'=>$user_token_data['customer_ID_number'],
                'CC'=>$user_token_data['token'],
                'Tmonth'=>$user_token_data['Tmonth'],
                'Tyear'=>$user_token_data['Tyear'],
                'ClientName'=>$_REQUEST['full_name'],
                'ClientLName'=>$_REQUEST['biz_name'],
                'SendHesh'=>'True',
                'UTF8'=>'True',
                'Tash'=>$_REQUEST['Payments'],
                'FixTash'=>'True',
                //'ClientName'=>$userName_arr[0],
                //'ClientLName'=>$userName_arr[0],
                // 'allowFalse'=>'True',
                
            );
            $postData = '';
            //create name value pairs seperated by &
            foreach($params as $k => $v) 
            { 
                $postData .= $k . '='.$v.'&'; 
            }
            $postData = rtrim($postData, '&');
         
            $ch = curl_init();  
            
            curl_setopt($ch,CURLOPT_URL,$yaad_api_url);
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
            if($result['CCode'] == '0'){
                $yaad_return_url = outer_url('yaad_return/ok/')."?Id=".$result['Id']."&CCode=".$result['CCode']."&Amount=".$result['Amount']."&ACode=".$result['ACode']."&Order=".$pay_by_cc_log_id."&Payments=1&UserId=".$user_token_data['customer_ID_number']."&Hesh=".$result['Hesh']."";
                return $this->redirect_to($yaad_return_url);
            }
            else{
                SystemMessages::add_err_message("הפעולה נכשלה, אחד הפרטים אינם נכונים. אנא נסה שוב.");
                return $this->eject_redirect();
            }
        }
        else{
            $yaad_charset_str = '';
            if(get_config("yaad_charset") == "windows-1255"){
                $yaad_charset_str = ' accept-charset="windows-1255"';
            }		
            echo '
                <form name="YaadPay" '.$yaad_charset_str.' action="'.$yaad_api_url.'" method="post" >
                <INPUT TYPE="hidden" NAME="Masof" value="'.get_config('yaad_api_masof').'" >
                <INPUT TYPE="hidden" NAME="action" value="pay" >
                <INPUT TYPE="hidden" NAME="Amount" value="'.$lounch_fee['price'].'" >
                <INPUT TYPE="hidden" NAME="Order" value="'.$pay_by_cc_log_id.'" >
                <INPUT TYPE="hidden" NAME="Info" value ="'.$details.'" >
                <input type="hidden" name="SendHesh" value="true">
                <INPUT TYPE="hidden" NAME="Tash" value="'.$_REQUEST['Payments'].'" >
                <INPUT TYPE="hidden" NAME="FixTash" value="True" >
                <input type="hidden" name="heshDesc" value="'.$details.'">
                <INPUT TYPE="hidden" NAME="MoreData" value="True" >
                <INPUT TYPE="hidden" NAME="street" value="'.$this->user['address'].'" >
                <INPUT TYPE="hidden" NAME="city" value="'.$this->user['city_name'].'" >
                <INPUT TYPE="hidden" NAME="phone" value="'.$this->user['phone'].'" >
                <INPUT TYPE="hidden" NAME="email" value="'.$this->user['email'].'" >
                
                </form>
                <p>טוען טופס מאובטח...</p>
                
                <script>YaadPay.submit(); </script>
            ';
        }
    }

    public function lounch_fee_token(){
      if(!isset($_REQUEST['token'])){
        return $this->redirect_to(inner_url());
      }
      $filter_arr = array(
        'token'=>$_REQUEST['token'],
        'user_id'=>$_REQUEST['user'],
        'id'=>$_REQUEST['row_id']
      );

      $token_results = Myleads_lounch_fee::get_list($filter_arr);

      if(!($token_results && isset($token_results[0]))){
        SystemMessages::add_err_message("פג תוקף הטוקן לכניסה אוטומטית");
        return $this->redirect_to(inner_url());
      }
      $update_arr = array('token'=>'');
      //remove token
      Myleads_lounch_fee::update($_REQUEST['row_id'],$update_arr);
      $token_result = $token_results[0];
      
      $log_in_user = Users::get_by_id($token_result['user_id']);
      //check if replace user
      $go_to_page = inner_url('payments/lounch_fee/?row_id='.$_REQUEST['row_id']);
      if((!$this->user) || $token_result['user_id'] != $this->user['id']){
        $login_with_sms = Global_settings::get()['login_with_sms'];
        $login_trace = UserLogin::add_login_trace($log_in_user['id'],$login_with_sms);
        
        if($login_with_sms){
            session__set('last_requested_url',$go_to_page);
            
            $this->send_login_sms_code($log_in_user['phone'],$login_trace['sms_code']);
            SystemMessages::add_success_message("רק עוד שלב אחד");
            $this->redirect_to(outer_url("userLogin/login/"));
        }
        else{
            $this->redirect_to($go_to_page);
        }
      }
      else{
        $this->redirect_to($go_to_page);
      }
    }


    protected function send_login_sms_code($user_phone, $sms_code){
        $this->data['sms_login_code'] = $sms_code;
        $msg = $this->include_ob_view('sms/login_sms.php');
        Helper::send_sms($user_phone,$msg);
    }

  }
?>