<?php
	class BookkeepingModule extends Module{
        
        public $add_models = array('myleads_lounch_fee','myleads_pay_by_cc_log','myleadsUser_bookkeeping');
      
        public function submit_hosting_pay(){
            return $this->submit_pay('renew_hosting');
        }

        public function submit_domain_pay(){
            return $this->submit_pay('renew_domain');
        }

        public function submit_pay($apply_product_type){
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
                if(isset($_REQUEST['Payments'])){
                    $lounch_fee_data['tash'] = $_REQUEST['Payments'];
                }

                $lounch_fee_id = Myleads_lounch_fee::create($lounch_fee_data);
                $log_update = array(
                    'lounch_id' =>  $lounch_fee_id
                );
                Myleads_pay_by_cc_log::update($cc_log['id'],$log_update);
                $this->$apply_product_type();
            }
            return $this->redirect_to(inner_url('credits/buy_leads/'));
        }

        protected function renew_hosting($cc_log){
            MyleadsUser_bookkeeping::renew_hosting($cc_log['user_id']);
            SystemMessages::add_success_message("תוקף האחסון הוארך בשנה");
        }

        protected function renew_domain($cc_log){
            MyleadsUser_bookkeeping::renew_domain($cc_log['user_id']);
            SystemMessages::add_success_message("תוקף הדומיין הוארך בשנה");
        }

	}
?>