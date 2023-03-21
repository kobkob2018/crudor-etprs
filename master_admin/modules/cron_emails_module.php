<?php
	class cron_emailsModule extends Module{
        public $add_models = array("user_pending_emails","masterUser_leads");

        //each minute cronjob at cron_master_controller.php
        public function send_pending_emails(){
            $pending_emails = User_pending_emails::get_list();
            foreach($pending_emails as $pending_email){
                
                $ok_send = $this->check_timing($pending_email['send_times']);
               
                if($ok_send){
                    $this->controller->send_email($pending_email['email_to'], $pending_email['title'] ,$pending_email['content']);

                    if($pending_email['lead_id'] != '0' && $pending_email['lead_id'] != ''){
                        $update_lead_arr = array('send_state'=>'1');
                        MasterUser_leads::update($pending_email['lead_id'],$update_lead_arr);
                    }

                    User_pending_emails::delete($pending_email['id']);
                }
            }
        }

        protected $weekdays_index = array(
            'Sun'=>'1',
            'Mon'=>'2',
            'Tue'=>'3',
            'Wed'=>'4',
            'Thu'=>'5',
            'Fri'=>'6',
            'Sat'=>'7'
        );

        protected function check_timing($time_groups_json){
            if($time_groups_json == ""){
                return true;
            }
            $now_arr = $this->get_now_arr();

            $today_index = $now_arr['day'];
            $time_groups = json_decode($time_groups_json, true);
            $ok_found = false;
            foreach($time_groups as $time_group){
                if($ok_found){
                    continue;
                }
                if(isset($time_group['d'][$today_index])){
                    if((
                        $time_group['hf'] > $now_arr['hour'] || (
                            $time_group['hf'] == $now_arr['hour'] && 
                            $time_group['mf'] >= $now_arr['hour'])
                        )
                         && 
                        (
                            $time_group['ht'] < $now_arr['hour'] || (
                                $time_group['ht'] == $now_arr['hour'] && 
                                $time_group['mt'] <= $now_arr['hour'])
                        )){
                            $ok_found = true;
                            break;
                    }
                }
            }
            return $ok_found;
        }

        protected $now_arr = array();

        protected function get_now_arr(){
            if(!empty($this->now_arr)){
                return $this->now_arr;
            }
            $now_timestamp = time();
            $today = date('D',$now_timestamp);
            $today_index = $this->weekdays_index[$today];
            $hour = date('H',$now_timestamp);
            $minute = date('i',$now_timestamp);
            $this->now_arr['day'] = $today_index;
            $this->now_arr['hour'] = $hour;
            $this->now_arr['minute'] = $minute;
            return $this->now_arr;
        }


        //monthly cronjob at cron_master_controller.php
        public function monthly_cleanup(){
            User_pending_emails::clean_month_old_emails();   
        }
	}
?>