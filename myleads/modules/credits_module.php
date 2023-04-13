<?php
	class CreditsModule extends Module{
        
        public $add_models = array('myleads_lounch_fee','myleads_pay_by_cc_log','leads_user');
      
        public function submit_pay(){
            $action_data = $this->action_data;
            $cc_log = $action_data['cc_log'];
            $success = $action_data['success'];
            if($success != 'ok'){
                SystemMessages::add_err_message($cc_log['details']. " - אירעה שגיאה בעת התשלום, אנא נסה שוב.");
                return $this->redirect_to(inner_url('credits/buy_leads/'));
            }
            else{
                // success = ok
                $lounch_fee_data = array(
                    'user_id'=>$cc_log['user_id'],
                    'pay_status'=>'1',
                    'price'=>$cc_log['sum_total'],
                    'tash'=>'1',
                    'details'=>$cc_log['details'],
                    'order_id'=>$cc_log['id']
                );
                $lounch_fee_id = Myleads_lounch_fee::create($lounch_fee_data);
                $log_update = array(
                    'lounch_id' =>  $lounch_fee_id
                );
                Myleads_pay_by_cc_log::update($cc_log['id'],$log_update);

                $temp = explode( " ", $cc_log['details'] );
                $add_credits = $temp[1];
                Leads_user::add_credits_to_user($cc_log['user_id'],$add_credits);
                SystemMessages::add_success_message("הרכישה בוצעה בהצלחה - ". $cc_log['details']);
            }
            return $this->redirect_to(inner_url('credits/buy_leads/'));
        }
	}
?>