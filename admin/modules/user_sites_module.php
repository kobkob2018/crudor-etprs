<?php
	class user_sitesModule extends Module{

        public $add_models = array("sites");
		
        public function raff_list(){
            $sites_list = Sites::get_user_site_list();
            $this->add_data('user_sites_link_list',$sites_list? $sites_list: array());
            $this->include_view('user/site_list.php');
        }

	}
?>