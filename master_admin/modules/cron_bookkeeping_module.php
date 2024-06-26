<?php
	class Cron_bookkeepingModule extends Module{
        public $add_models = array("user_pending_emails","user_bookkeeping","auto_login_token");

        //each minute cronjob at cron_master_controller.php
        public function daily_alerts(){
            
            $admin_info = Users::find(array('email'=>get_config('alerts_admin_email')));
            
            $hosting_alert_timings = array('1','2','3','7','14','30');
            foreach($hosting_alert_timings as $days_before){
                $users_to_alert = User_bookkeeping::get_users_to_alert_hosting($days_before);
                foreach($users_to_alert as $bookeeping_info){
                    $bookeeping_info['hosting_days'] = $days_before;
                    $this->send_hosting_alert($bookeeping_info);
                    if($days_before == '14' && $admin_info){
                        $this->send_hosting_alert_to_admin($bookeeping_info,$admin_info);
                    }
                }
            }

            $domain_alert_timings = array('1','2','3','7','14','30');
            foreach($domain_alert_timings as $days_before){
                $users_to_alert = User_bookkeeping::get_users_to_alert_domain($days_before);
                foreach($users_to_alert as $bookeeping_info){
                    $bookeeping_info['domain_days'] = $days_before;
                    $this->send_domain_alert($bookeeping_info);
                    if($days_before == '14' && $admin_info){
                        $this->send_domain_alert_to_admin($bookeeping_info,$admin_info);
                    }
                }
            }
            if($admin_info){
                $days_before = '14';
                $users_to_alert = User_bookkeeping::get_users_to_alert_domain_admin($days_before);
                foreach($users_to_alert as $bookeeping_info){
                    $bookeeping_info['domain_days'] = $days_before;
                    $this->send_domain_alert_to_admin_only($bookeeping_info,$admin_info);
                }
            }
        }

        protected function send_domain_alert_to_admin_only($email_info,$admin_info){
            $email_content = $this->controller->include_ob_view('emails_send/bookeeping_domain_admin_only_alert.php',$email_info);
            return $this->append_email($admin_info,"התראה למנהל - הארכת תוקף דומיין צד שלישי",$email_content);
        }

        protected function send_domain_alert_to_admin($email_info,$admin_info){
            $email_content = $this->controller->include_ob_view('emails_send/bookeeping_domain_admin_alert.php',$email_info);
            return $this->append_email($admin_info,"התראה למנהל - הארכת תוקף דומיין",$email_content);
        }

        protected function send_hosting_alert_to_admin($email_info,$admin_info){
            $email_content = $this->controller->include_ob_view('emails_send/bookeeping_hosting_admin_alert.php',$email_info);
            return $this->append_email($admin_info,"התראה למנהל - הארכת תוקף אחסון אתר",$email_content);
        }

        protected function send_domain_alert($email_info){
            $token_info = $this->prepare_auto_login_token($email_info['user']['id']);
            $email_info['token'] = $token_info['token'];
            $email_info['token_id'] = $token_info['id'];
            $email_content = $this->controller->include_ob_view('emails_send/bookeeping_domain_alert.php',$email_info);
            return $this->append_email($email_info['user'],"הארכת תוקף דומיין",$email_content);

        }

        protected function send_hosting_alert($email_info){
            $token_info = $this->prepare_auto_login_token($email_info['user']['id']);
            $email_info['token'] = $token_info['token'];
            $email_info['token_id'] = $token_info['id'];
            $email_content = $this->controller->include_ob_view('emails_send/bookeeping_hosting_alert.php',$email_info);
            return $this->append_email($email_info['user'],"הארכת תוקף אחסון אתר",$email_content);
        }

        protected function prepare_auto_login_token($user_id){
            $token = time().rand(100000,999999);
            $token_info = array(
                'token'=>md5($token),
                'user_id'=>$user_id
            );
            $return_arr = array('token'=>$token);
            $return_arr['id'] = Auto_login_token::create($token_info);
            return $return_arr;
        }

        protected function append_email($user_info,$email_title,$email_content){
            $send_times = '[{"hf":"08","mf":"00","ht":"18","mt":"00","d":{"1":"on","2":"on","3":"on","4":"on","6":"on","7":"on"}}]';
            $email_pending_message = array(
                'user_id'=>$user_info['id'],
                'email_to'=>$user_info['email'],
                'phone_to'=>$user_info['phone'],
                'title'=>$email_title,
                'content'=>$email_content,
                'sms_content'=>'',
                'send_times'=>$send_times
            );

            return User_pending_emails::create($email_pending_message);
               
        }
	}
?>