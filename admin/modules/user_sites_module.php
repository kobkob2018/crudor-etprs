<?php
	class user_sitesModule extends Module{

        public $add_models = array("sites");
		
        public function raff_list(){
            $info = array(
                'site_list_type'=>'normal'
            );
            if(isset($_REQUEST['master_list'])){
                if(Helper::user_is('master_admin',$this->user)){
                    $sites_list = Sites::get_list();
                    $info['site_list_type'] = 'master_admin';
                }
                else{
                    SystemMessages::add_err_message("אינך רשאי לצפות בתוכן זה");
                    return $this->redirect_to(inner_url("userSites/list/"));
                }
            }
            else{
                $sites_list = Sites::get_user_site_list();
            }

            $this->add_data('user_sites_link_list',$sites_list? $sites_list: array());
            $this->include_view('user/site_list.php',$info);
        }

	}
?>