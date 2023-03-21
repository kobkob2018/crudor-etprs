<?php
	class MyleadsModule extends Module{
        
        public $add_models = array("users","leads_user");
        //good place to colect global data of the workon site, the user, etc...
        public function init_layout(){
            $user = Users::get_loged_in_user();
            if($user){
                $this->controller->user = Leads_user::get_leads_user_data($user);
            }
            $this->add_data('meta_title',"מערכת ניהול לידים - איי אל ביז");
            return;
        }

        public function handle_access_default(){
            return $this->handle_access_login_only();
        }

        public function handle_access_login_only(){
            $action_name = $this->action_data;
             if(!$this->user){
                if(strpos($action_name, 'ajax_') === 0){
                    $print_result = array(
                        'success'=>false,
                        'err_message'=>'User loged out',
                        'fail_reason'=>'logout_user'
                    );
                    $this->controller->print_json_page($print_result);
                }
                else{
                    session__set('last_requested_url',current_url());
                    $this->redirect_to(inner_url('userLogin/login/'));
                }
                return false;
            }
            session__unset('last_requested_url');
            return true;
        }

        public function handle_access_loggedout_only(){
            if($this->user){
                $this->redirect_to(outer_url(''));
                return false;
            }
            return true;
        }

        public function get_assets_dir(){
            $master_site = Sites::get_by_domain(get_config('master_domain'));
            $assets_dir = Sites::get_site_asset_dir($master_site);
            return $assets_dir;
        }

        public function add_global_essential_ajax_info(){
            $print_result = $this->action_data;
            $print_result['system'] = 'myleads';
            $user = $this->controller->user;
            $user_arr = array();
            $user_arr['login'] = '0';
            if($this->user){
                $user_arr['login'] = '1';
                $user_params = array(
                    "id",
                    "email",
                    "end_date",
                    "full_name",
                    "access_refunds",
                    "lead_price",
                    "lead_credit",
                    "phone",
                    "free_send",
                    "open_mode",
                    "auto_send",
                    "has_special_closed_lead_alert",
                );
                foreach($user_params as $user_param){
                    $user_arr[$user_param] = $this->user[$user_param];
                }
            }	
            $print_result['user'] = $user_arr;

            $print_result['user'] = $user;
            return $print_result;
        }

	}
?>