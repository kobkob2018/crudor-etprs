<?php
	class user_sitesModule extends Module{

        public $add_models = array("sites");
		
        public function raff_list(){
            $user_sites_list = Sites::get_user_site_list();
            $user_sites_by_index = Helper::eazy_index_arr_by('id',$user_sites_list);
            $info = array(
                'site_list_type'=>'normal',
                'user_sites_by_id'=>$user_sites_by_index
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
                $sites_list = $user_sites_list;
            }

            $this->add_data('user_sites_link_list',$sites_list? $sites_list: array());
            $this->include_view('user/site_list.php',$info);
        }

        public function get_site_users_list_for_item_assign(){
            $this->controller->add_model("site_users");
            $site_id = $this->controller->data['work_on_site']['id'];
            $users_list = array();
            $site_users = Site_users::get_list(array('site_id'=>$site_id),"user_id");
            foreach($site_users as $site_user){
              $find_user = Users::get_by_id($site_user['user_id'],'id, full_name');
              if($find_user){
                $users_list[] = $find_user;
              }
            }
            return $users_list;
          }
	}
?>