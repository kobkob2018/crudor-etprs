<?php
	class HomepageModule extends Module{
        public $add_models = array("sitePages");
        public function pages_list(){
            $action_data = $this->decode_action_data_arr();
            $tag = false;
            if(isset($action_data['tag'])){
                $tag = trim($action_data['tag']);
            }
            $pages_list = SitePages::get_home_page_list($tag);
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