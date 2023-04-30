<?php
	class HomepageModule extends Module{
        public $add_models = array("sitePages");
        public function pages_list(){
            $pages_list = SitePages::get_home_page_list();
            if(!$pages_list){
                $pages_list = array();
            }
            $info = array(
                'pages_list'=>$pages_list
            );
            $this->include_view('homepage/pages_list.php', $info);
        }
	}
?>