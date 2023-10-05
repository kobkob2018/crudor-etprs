<?php
	class HomepageModule extends Module{
        public $add_models = array("sitePages");
        public function pages_list(){
            $action_data = $this->decode_action_data_arr();
            $tag = false;
            if(isset($action_data['tag'])){
                $tag = trim($action_data['tag']);
            }
            $paging = false;
            $paging_page = '0';
            if(isset($_GET['p'])){
                $paging_page = $_GET['p'] - 1;
            }
            if(isset($action_data['page_limit'])){
                $paging = array(
                    'limit'=>trim($action_data['page_limit']),
                    'page'=>$paging_page
                );
            }
            $info = SitePages::get_home_page_list($tag,$paging);
            if(!$info['list']){
                $info['list'] = array();
            }
            
            $this->include_view('homepage/pages_list.php', $info);
        }
	}
?>