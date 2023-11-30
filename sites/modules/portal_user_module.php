<?php
// to debug here, put in js console: help_debug_forms();

	class Portal_userModule extends Module{
        
        public $add_models = array("sitePortal_user");
        public function use(){
            $user_id = $this->controller->data['page']['user_id'];
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
        }
        public function logo_img(){
            $this->include_view('portal_user/logo.php');
        }

	}
?>