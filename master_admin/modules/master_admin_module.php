<?php
	class master_adminModule extends Module{

        //good place to colect global data of the workon site, the user, etc...
        public function init_layout(){
            $this->controller->add_model('sites');
            $this->add_data('meta_title',"ניהול ראשי - איי אל ביז");
            return;
        }

        public function handle_access_default(){
            return $this->handle_access_master_admin_only();
        }

        public function handle_access_login_only(){
            
            $action_name = $this->action_data;
             if(!$this->user){
                if(strpos($action_name, 'ajax_') === 0){
                    $this->controller->print_json_page(array()); 
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

        public function handle_access_master_admin_only(){
            if(!$this->handle_access_login_only()){
                return false;
            }

            if(!Helper::user_is('master_admin',$this->user)){
                SystemMessages::add_err_message('אינך רשאי לצפות בתוכן זה');
                $this->redirect_to(inner_url('tasks/list/'));
                return false;
            }
            return true;
        }

        public function handle_access_user_is(){
            $needed_roll = $this->action_data;
            $user = $this->user;

            $user_is = Helper::user_is($needed_roll,$user);
            if($user_is){
                return true;
            }

            SystemMessages::add_err_message('אינך רשאי לצפות בתוכן זה');
            $this->redirect_to(inner_url(''));
            return;
        }

        public function get_assets_dir(){
            $master_site = Sites::get_by_domain(get_config('master_domain'));
            $assets_dir = Sites::get_site_asset_dir($master_site);
            return $assets_dir;
        }
	}
?>