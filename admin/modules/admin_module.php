<?php
	class adminModule extends Module{

        //good place to colect global data of the workon site, the user, etc...
        public function init_layout(){
            $this->controller->add_model('sites');
            $work_on_site = Sites::get_user_workon_site();
            if($work_on_site){
                $this->add_data('meta_title',"ניהול אתר - ".$work_on_site['domain']);
            }
            else{
                $this->add_data('meta_title',get_config('site_title'));
            }
            $this->add_data('work_on_site',$work_on_site);
            return;
        }

        public function handle_access_default(){
            return $this->handle_access_workon_site_only();
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

        public function handle_access_workon_site_only(){
            if(!$this->handle_access_login_only()){
                return false;
            }

            $this->controller->add_model('sites');
            $work_on_site = Sites::get_user_workon_site();
            if(!$work_on_site){
                $this->redirect_to(inner_url('userSites/list/'));
                return false;
            }
            return true;
        }

        public function handle_access_user_is(){

            if(!$this->handle_access_workon_site_only()){
                return false;
            }
            $this->controller->add_model('sites');
            
            $needed_roll = $this->action_data;
            $user = $this->user;
            $work_on_site = Sites::get_user_workon_site();

            $user_is = Helper::user_is($needed_roll,$user,$work_on_site);
            if($user_is){
                return true;
            }

            SystemMessages::add_err_message('אינך רשאי לצפות בתוכן זה');
            $this->redirect_to(inner_url(''));
            return;
        }

        public function get_assets_dir(){
            $assets_dir = Sites::get_user_workon_site_asset_dir();
            return $assets_dir;
        }

        public function add_global_essential_ajax_info(){
            $print_resut = $this->action_data;
            $print_resut['system'] = 'admin';
            return $print_resut;
        }

	}
?>