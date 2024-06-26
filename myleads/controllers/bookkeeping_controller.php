<?php

  class BookkeepingController extends CrudController{
    public $add_models = array('user_cc_token','myleadsUser_bookkeeping','myleads_pay_by_cc_log');

    protected function handle_access($action){
		switch ($action){
		  case 'auth':
			return true;
			break;
		  default:
			return parent::handle_access(($action));
			break;
		  
		}
	}

    protected function get_info(){
        $info = MyleadsUser_bookkeeping::find(array('user_id'=>$this->user['id']));
        $info['hostPriceYear'] = round($info['hostPriceMon']*12*1.17);
        $info['hosting_days'] = days_to($info['hostEndDate']);
        $info['domain_days'] = days_to($info['domainEndDate']);
        $info['have_hosting'] = ($info['hostPriceMon'] > 0);
        $info['have_domain'] = ($info['domainPrice'] > 0);
        $info['domainPriceTotal'] = round($info['domainPrice']*1.17);
        $info['allow_host_payment'] = $info['have_hosting'] && ($info['hosting_days'] < 61);
        $info['allow_domain_payment'] = $info['have_domain'] && ($info['domain_days'] < 61);
        $info['user_cc_tokens'] =  User_cc_token::get_list(array('user_id'=>$this->user['id']));
        return $info;
    }

    public function view(){
        $info = $this->get_info();
        if(!($info['have_hosting'] || $info['have_domain'])){
            SystemMessages::add_err_message("אינך צריך לשלם על אחסון אתר");
            return $this->redirect_to(inner_url(""));
        }    
        return $this->include_view('bookkeeping/view.php',$info); 
    }

    public function auth(){
        if(isset($_REQUEST['msg_id']) && isset($_REQUEST['token'])){
            return $this->auto_login_with_token($_REQUEST['msg_id'],$_REQUEST['token'],'view');
        }
    }

    protected function auto_login_with_token($msg_id, $token, $login_action){
        $this->add_model('auto_login_token');
        $md_token = md5($token);
        $token_filter = array('id'=>$msg_id,'token'=>$md_token);
        $token_find = Auto_login_token::find($token_filter);
        if($token_find){
            Auto_login_token::delete($msg_id);
            $user_id = $token_find['user_id'];
            $user_loged_in = false;
            if($this->user && $this->user['id'] == $user_id){
                $user_loged_in = true;
            }
            if(!$user_loged_in){
                UserLogin::add_login_trace($user_id,false);
            }
        }
        $this->set_layout('blank');
        $redirect_to = inner_url("bookkeeping/$login_action/");
        return $this->redirect_to($redirect_to);
    }

    public function send_to_yaad_hosting(){
        $info = $this->get_info();
        if(!$info['allow_host_payment']){
            SystemMessages::add_err_message("אין אפשרות לחדש את תוקף האחסון. אנא פנה לשירות לקוחות למידע נוסף");
            return $this->redirect_to(inner_url("bookkeeping/view/"));
        }
        $new_p = $info['hostPriceYear'];
        $details = "חידוש תוקף אכסון לשנה";
        return $this->send_to_yaad($new_p,$details,'12');
    }

    public function send_to_yaad_domain(){
        $info = $this->get_info();
        if(!$info['allow_domain_payment']){
            SystemMessages::add_err_message("אין אפשרות לחדש את תוקף הדומיין. אנא פנה לשירות לקוחות למידע נוסף");
            return $this->redirect_to(inner_url("bookkeeping/view/"));
        }
        $new_p = $info['domainPriceTotal'];
        $details = "חידוש תוקף דומיין לשנה";
        return $this->send_to_yaad($new_p,$details);
    }

    public function send_to_yaad($new_p,$details,$allow_payments = '1'){

        $pay_by_cc_log_data = array(
            'sum_total'=>$new_p,
            'details'=>$details,
            'user_id'=>$this->user['id'],
            'handle_module'=>'bookkeeping',
            'handle_method'=>'submit_hosting_pay',
            'lounch_id'=>'0'
        );

        $pay_by_cc_log_id = Myleads_pay_by_cc_log::create($pay_by_cc_log_data);
        
        $yaad_api_url = get_config('yaad_api_url');
        if($_REQUEST['use_token']!='0'){

            $user_token_data = User_cc_token::find(array('user_id'=>$this->user['id'],'L4digit'=>$_REQUEST['use_token']));

            if((!$user_token_data)){
                SystemMessages::add_err_message("אירעה שגיאה בחיוב");
                return $this->redirect_to(inner_url("bookkeeping/view/"));
            }
					
            $params = array(
                'Masof'=>get_config('yaad_api_masof'),
                'action'=>'soft',
                'PassP'=>get_config('yaad_api_token_pass'),
                'Token'=>'True',
                'Order'=>$pay_by_cc_log_id,
                'Amount'=>$new_p,
                'Info'=>$details,
                'UserId'=>$user_token_data['customer_ID_number'],
                'CC'=>$user_token_data['token'],
                'Tmonth'=>$user_token_data['Tmonth'],
                'Tyear'=>$user_token_data['Tyear'],
                'ClientName'=>$_REQUEST['full_name'],
                'ClientLName'=>$_REQUEST['biz_name'],
                'Tash'=>$_REQUEST['Tash'],
                'SendHesh'=>'True',
                'UTF8'=>'True',                
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

                $yaad_return_url = outer_url('yaad_return/ok/')."?Id=".$result['Id']."&CCode=".$result['CCode']."&Amount=".$result['Amount']."&ACode=".$result['ACode']."&Order=".$pay_by_cc_log_id."&Payments=".$params['Tash']."&UserId=".$user_token_data['customer_ID_number']."&Hesh=".$result['Hesh']."";

                return $this->redirect_to($yaad_return_url);
            }
            else{
                SystemMessages::add_err_message("הפעולה נכשלה, אחד הפרטים אינם נכונים. אנא נסה שוב.");
                SystemMessages::add_err_message("code: ".$result['CCode']);
                return $this->redirect_to(inner_url('bookkeeping/view/'));
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
                <INPUT TYPE="hidden" NAME="Amount" value="'.$new_p.'" >
                <INPUT TYPE="hidden" NAME="Order" value="'.$pay_by_cc_log_id.'" >
                <INPUT TYPE="hidden" NAME="Info" value ="'.$details.'" >
                <input type="hidden" name="SendHesh" value="true">
                <INPUT TYPE="hidden" NAME="Tash" value="'.$allow_payments.'" >
                <INPUT TYPE="hidden" NAME="FixTash" value="False" >
                <input type="hidden" name="heshDesc" value="'.$details.'">
                <INPUT TYPE="hidden" NAME="MoreData" value="True" >
                <INPUT TYPE="hidden" NAME="street" value="'.$this->user['address'].' " >
                <INPUT TYPE="hidden" NAME="city" value="'.$this->user['city_name'].' " >
				<INPUT TYPE="hidden" NAME="zip" value=" " >
                <INPUT TYPE="hidden" NAME="phone" value="'.$this->user['phone'].'" >
                <INPUT TYPE="hidden" NAME="email" value="'.$this->user['email'].'" >
                
                </form>
                <p>טוען טופס מאובטח...</p>
                
                <script>YaadPay.submit(); </script>
            ';
        }
    }

  }
?>