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
            if(!$this->handle_admin_domains_access()){
                return false;
            }
            return $this->handle_access_site_user_is('admin');
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

        public function handle_access_site_user_is($needed_roll = false){
            
            if(!$this->handle_access_workon_site_only()){
                return false;
            }
            
            $this->controller->add_model('sites');
            if(!$needed_roll){
                $needed_roll = $this->action_data;
            }
            
            $user = $this->user;
            $work_on_site = Sites::get_user_workon_site();
            
            $user_is = Helper::user_is($needed_roll,$user,$work_on_site);
            if($user_is){
                return true;
            }
            
            SystemMessages::add_err_message('אינך רשאי לצפות בתוכן זה');
            
            $this->redirect_to(inner_url('tasks/list/'));
            return;
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


        public function handle_access_user_can(){ 
            $this->controller->add_model('sites');
            $user_can = $this->action_data;
            $user = $this->user;
            $work_on_site = Sites::get_user_workon_site();

            $user_is_admin = Helper::user_is('admin',$user,$work_on_site);
            if($user_is_admin){
                return true;
            }
            $user_is_author = Helper::user_is('author',$user,$work_on_site);
            if($user_is_author){
                $site_user_can = $this->get_site_user_can();
                foreach($site_user_can as $permittion_to=>$can){
                    if($permittion_to == $user_can){
                        return true;
                    }
                }
            }
            SystemMessages::add_err_message('אינך רשאי לצפות בתוכן זה');
            $this->redirect_to(inner_url(''));
            return;
        }

        protected $site_user_can = false;
        public function get_site_user_can(){
            if($this->site_user_can){
                return $this->site_user_can;
            }
            $user = $this->user;
            $work_on_site = Sites::get_user_workon_site();
            $user_is = Helper::user_is('author',$user, $work_on_site);
            if(!$user_is){
                if(isset($_REQUEST['checkme'])){
                    exit("not good");
                    
                  }
                $this->site_user_can = array();
                return $this->site_user_can;
            }
            
            $user_can_list = TableModel::simple_get_list_by_table_name(array('user_id'=>$user['id'],'site_id'=>$work_on_site['id']),'site_user_can','permission_to');
            if(isset($_REQUEST['checkme'])){
                print_r_help($user_can_list,'from module');
                
              }
            if(!$user_can_list){
                $this->site_user_can = array();
                return $this->site_user_can;
            }
            $this->site_user_can = array();
            foreach($user_can_list as $premittion){
                $this->site_user_can[$premittion['permission_to']] = '1';
            }
            return $this->site_user_can;
        }

        public function get_assets_dir(){

            $info = $this->action_data;
            if(isset($info['relative_site']) && $info['relative_site'] == 'master'){
                $master_site = Sites::get_by_domain(get_config('master_domain'));
                $assets_dir = Sites::get_site_asset_dir($master_site);
                return $assets_dir;
            }
            
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