<?php
	class sitesModule extends Module{
        public $add_models = array("biz_categories",
                    "sitePages",
                    "SiteCat_phone",
                    "siteBiz_forms",
                    "siteSite_styling");

        public function init_layout(){
            $this->controller->add_model('sites');
            $site = Sites::get_current_site();
            $this->add_asset_mapping(Sites::$asset_mapping);
            
            if($site){
                
                $this->add_data('page_meta_title', $site['meta_title']);
            }
            else{
                $this->add_data('page_meta_title',get_config('site_title'));
            }
            $this->add_data('site',$site);
            $site_styling = SiteSite_styling::get_current_site_styling();
            $this->add_data('site_styling',$site_styling);
            return;
        }        

        public function handle_access_default(){
            return true;
        }

        public function get_assets_dir(){
            $info = $this->action_data;
            if(isset($info['relative_site']) && $info['relative_site'] == 'master'){
                $master_site = Sites::get_by_domain(get_config('master_domain'));
                $assets_dir = Sites::get_site_asset_dir($master_site);
                return $assets_dir;
            }
            else{
                $assets_dir = Sites::get_current_site_asset_dir();
                return $assets_dir;
            }
        }

        public function add_global_essential_ajax_info(){
            $print_resut = $this->action_data;
            $print_resut['system'] = 'sites';
            return $print_resut;
        }


            //to be overrriten by main_module..
        public function proccess_body_modules(){
            $body_output = $this->action_data;  
            $body_output = $this->clean_mobile_conditions($body_output);
            $body_output = $this->setup_cat_phone_text($body_output);  
            $body_output = $this->setup_text_modules($body_output);
            return $body_output;
        }

        protected function clean_mobile_conditions($text){
            $text = $this->clean_conditions($text,"{{%-mobile-%}}","{{%-endmobile-%}}",is_mobile());
            $text = $this->clean_conditions($text,"{{%-desktop-%}}","{{%-enddesktop-%}}",!is_mobile());
            return $text;
        }
    
        protected function clean_conditions($text,$condition_start_tag,$condition_end_tag,$condition_bool){
            $text_return = "";
            $text_arr = explode($condition_start_tag,$text);
            foreach($text_arr as $text_part){
                
                $mobile_arr = explode($condition_end_tag, $text_part);
                
                if(isset($mobile_arr[1])){
                if($condition_bool){
                    $text_return .= $mobile_arr[0];
                }
                $text_return .= $mobile_arr[1];
                }
                else{
                $text_return .= $mobile_arr[0];
                }
            }
            return $text_return;
        }

        protected function setup_cat_phone_text($text){
            $biz_form_data = siteBiz_forms::get_current_biz_form();
            $phone_info = array(
              'phone'=>'',
              'display_class'=>''
            );
            if($biz_form_data){
              $cat_tree = Biz_categories::get_item_parents_tree($biz_form_data['cat_id'],'*');
              $phone_info = SiteCat_phone::get_category_phone_info($cat_tree);
            }
            $text = str_replace("{{cat_phone}}",$phone_info['phone'],$text);
            $text = str_replace("{{cat_phone_display}}",$phone_info['display_class'],$text);
            return $text;
        }

        protected function setup_text_modules($init_text){
            $return_text = "";
            $return_text_arr = explode("{{%", $init_text);
      
            foreach($return_text_arr as $body_module_part){
              $text_module_arr = explode("%}}", $body_module_part);
              if(!isset($text_module_arr[1])){
                $return_text .= $text_module_arr[0];
              }
              else{
                $module_call_str = $text_module_arr[0];
                $module_call_arr = explode("|",$module_call_str);
                foreach($module_call_arr as $call_key=>$call_str){
                  $module_call_arr[$call_key] = trim($call_str);
                }
                if(isset($module_call_arr[0]) && isset($module_call_arr[1]) && isset($module_call_arr[2])){
                  $module_data = "";
                  if(isset($module_call_arr[3])){
                    $module_data = $module_call_arr[3];
                  }
                  
                  if($module_call_arr[0] == 'mod'){
                    ob_start();
                    $this->call_module($module_call_arr[1],$module_call_arr[2],$module_data);
                    $return_text .= ob_get_clean();
                  }
                  if($module_call_arr[0] == 'view'){
                    ob_start();
                    $this->include_view($module_call_arr[1],$module_data);
                    $return_text .= ob_get_clean();
                  }
      
                }
                $return_text .= $text_module_arr[1];
              }
            }
            return $return_text;
        }

	}
?>