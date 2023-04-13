<?php
	class User_lounch_feeModule extends Module{
        
        public $add_models = array('myleads_lounch_fee');
      

        public function submit_pay(){
            $action_data = $this->action_data;
            $cc_log = $action_data['cc_log'];
            $success = $action_data['success'];
            if($success != 'ok'){
                SystemMessages::add_err_message($cc_log['details']. " - אירעה שגיאה בעת התשלום, אנא נסה שוב.");
                return $this->redirect_to(inner_url('payments/lounch_fee/?row_id='.$cc_log['lounch_id']));
            }
            else{
                // success = ok
                $lounch_update_data = array('pay_status'=>'1');
                Myleads_lounch_fee::update($cc_log['lounch_id'],$lounch_update_data);
                SystemMessages::add_success_message("התשלום בוצע בהצלחה");
            }
            return $this->redirect_to(inner_url('payments/lounch_fee_list/'));
        }
	}
?>