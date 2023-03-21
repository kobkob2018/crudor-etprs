<?php
	class Site_menusModule extends Module{

        public $add_models = array("siteMenuItems");

        public function right_menu(){
            $data = $this->controller->data;
            $page_id = -1;
            if(isset($data['page'])){
                $page_id = $data['page']['id'];
            }
            $right_menu_items = SiteMenuItems::match_page_menu_items($data['site']['id'], SiteMenuItems::$menu_type_list['right'], $page_id);
            
            $this->add_data('right_menu_items',$right_menu_items);
            $this->include_view('site_menus/right_menu.php');
        }


        public function top_menu(){
            $data = $this->controller->data;
            $page_id = -1;
            if(isset($data['page'])){
                $page_id = $data['page']['id'];
            }
            $top_menu_items = SiteMenuItems::get_menu_items_tree($data['site']['id'], SiteMenuItems::$menu_type_list['top'], $page_id);
            
            $this->add_data('top_menu_items',$top_menu_items);
            $this->include_view('site_menus/top_menu.php');
        }

        public function bottom_menu(){
            $data = $this->controller->data;
            $bottom_menu_items = SiteMenuItems::get_menu_items_tree($data['site']['id'], SiteMenuItems::$menu_type_list['bottom']);
            $this->add_data('bottom_menu_items',$bottom_menu_items);
            $this->include_view('site_menus/bottom_menu.php');
        }

	}
?>