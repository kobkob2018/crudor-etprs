<?php

  class CreditsController extends CrudController{
    public $add_models = array('user_cc_token','myleads_pay_by_cc_log');

    public function buy_leads(){
        $this->add_model('leads_user');
        $this->data['user_lead_settings'] = Leads_user::get_leads_user_data($this->user);
        $user_cc_tokens = User_cc_token::get_list(array('user_id'=>$this->user['id']));
        $info = array('user_cc_tokens'=>$user_cc_tokens);
        return $this->include_view('credits/buy_leads.php',$info);        
    }

    public function send_to_yaad(){
        $buy_c = (int)$_REQUEST['num_credit'];

		$this->data['user_lead_settings'] = Leads_user::get_leads_user_data($this->user);	
        if(!($buy_c > 0) ){
            SystemMessages::add_err_message("אירעה שגיאה. אנא נסה שוב");
            return $this->redirect_to(inner_url());   
        }

        $new_p = $buy_c * $this->data['user_lead_settings']['lead_price'];

        $details = "קניית"." ".$buy_c." "."לידים";

        $pay_by_cc_log_data = array(
            'sum_total'=>$new_p,
            'details'=>$details,
            'user_id'=>$this->user['id'],
            'handle_module'=>'credits',
            'handle_method'=>'submit_pay',
            'lounch_id'=>'0'
        );

        $pay_by_cc_log_id = Myleads_pay_by_cc_log::create($pay_by_cc_log_data);
        
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
                'SendHesh'=>'True',
                'UTF8'=>'True',
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
                SystemMessages::add_err_message("code: ".$result['CCode']);
                return $this->redirect_to(inner_url('payments/list/'));
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
                <INPUT TYPE="hidden" NAME="Tash" value="1" >
                <INPUT TYPE="hidden" NAME="FixTash" value="True" >
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