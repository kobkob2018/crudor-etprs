<?php
// to debug here, put in js console: help_debug_forms();

	class Portal_userModule extends Module{
        
        public $add_models = array("sitePortal_user","sitePortal_styling");
        public function use(){
            $data = $this->action_data;
            if(!isset($data['user_id'])){
                return;
            }
            $user_id = $data['user_id'];
            $portal_user = SitePortal_user::find(array('user_id'=>$user_id));
            if(!$portal_user){
                return;
            }
            $this->add_data('is_portal_view',true);
            $this->add_asset_mapping(SitePortal_user::$asset_mapping);
            $this->controller->data['portal_user'] = $portal_user;
            if(!isset($this->controller->data['text_replace'])){
                $this->controller->data['text_replace'] = array();
            }
            $portal_user_params = array(
                'label','logo','link','phone','city_name'
            );
            foreach($portal_user_params as $param){
                $this->controller->data['text_replace']['portal_'.$param] = $portal_user[$param];
                if($portal_user[$param] == "hidden"){
                    $this->controller->data['text_replace']['portal_class_'.$param] = "";
                }
                else{
                    $this->controller->data['text_replace']['portal_class_'.$param] = "hidden";
                }
            }
            $portal_styling = SitePortal_styling::find(array('user_id'=>$user_id));
            if(!$portal_styling){
                return;
            }
            $site_styling = array();
            if(isset($this->controller->data['site_styling'])){
                $site_styling = $this->controller->data['site_styling'];
            }
            $portal_styling_params = array(
                'header_html',
                'footer_html',
                'styling_tags',
                'bottom_styling_tags',
                'add_scrolling_requests'
            );
            foreach($portal_styling_params as $key){
                if($portal_styling[$key] != '' || !isset($site_styling[$key])){
                    $site_styling[$key] = $portal_styling[$key];
                }
            }
            $this->controller->data['site_styling'] = $site_styling;
        }

        public function logo_img(){
            $this->include_view('portal_user/logo.php');
        }

	}
?>