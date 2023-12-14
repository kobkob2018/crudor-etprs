<?php
	class QuotesModule extends Module{

        public $add_models = array("siteQuotes");
        public function print_cat(){        
            $action_data = $this->decode_action_data_arr();
            if(!isset($action_data['cat_id'])){
                return;
            }
            $this->add_asset_mapping(SiteQuotes::$asset_mapping);
            $cat_id = $action_data['cat_id'];
            $quote_list = SiteQuotes::get_cat_quotes($cat_id);
            
            if(!$quote_list){
                return;
            }
            $quote_cat = SiteQuotes::get_quotes_cat($cat_id);
            $open_state = 'closed';
            if(isset($action_data['state'])){
                $open_state = $action_data['state'];
            }
            $quote_cat['open_state'] = $open_state;
            if($quote_cat['custom_html'] == ""){
                $quote_cat['custom_html'] = $this->controller->include_ob_view('quotes/row_html_default.php');
            }
            if($quote_cat['title_html'] == ""){
                $quote_cat['title_html'] = $this->controller->include_ob_view('quotes/title_html_default.php');
            }
            $quote_cat['custom_html'] = $this->clean_browser_type_html($quote_cat['custom_html'],$action_data);
            $quote_cat['title_html'] = $this->clean_browser_type_html($quote_cat['title_html'],$action_data);
            
            foreach($quote_list as $key=>$quote){
                $img_url = $this->controller->file_url_of('quote_img',$quote['image'],'master');

                $quote['img_url'] = $img_url."?cache=".get_config('cash_version');
                $quote['html'] = $this->proccess_quote_html($quote_cat['custom_html'],$quote);
                $quote_list[$key] = $quote;
            }

            $info = array(
                'cat'=>$quote_cat,
                'list'=>$quote_list
            );
            $this->include_view('quotes/print_cat.php',$info);
        }

        public function print_user(){     
            $action_data = $this->decode_action_data_arr();
            $this->controller->add_model("siteQuotes_user");
            $this->add_asset_mapping(SiteQuotes_user::$asset_mapping);
            $user_id = $this->controller->data['page']['user_id'];

            $quote_user = SiteQuotes_user::find(array('user_id'=>$user_id));
            $quote_list = SiteQuotes::get_list(array('status'=>'1','user_id'=>$user_id));
            
            if(!$quote_list){
                return;
            }

            $open_state = 'closed';
            if(isset($action_data['state'])){
                $open_state = $action_data['state'];
            }
            $quote_user['open_state'] = $open_state;
            $quote_user['user_id'] = $user_id;
            
            if($quote_user['custom_html'] == ""){
                $quote_user['custom_html'] = $this->controller->include_ob_view('quotes/row_html_user.php');
            }
            if($quote_user['title_html'] == ''){
                $quote_user['title_html'] = $this->controller->include_ob_view('quotes/title_html_user.php');
            }
            $quote_user['custom_html'] = $this->clean_browser_type_html($quote_user['custom_html'],$action_data);
            $quote_user['title_html'] = $this->clean_browser_type_html($quote_user['title_html'],$action_data);
            
            $quote_user_procces_values = array();
            foreach($quote_user as $key=>$val){
                if($key != 'custom_html' && $key != 'title_html'){
                    $quote_user_procces_values['user_'.$key] = $val;
                }
            }

            foreach($quote_list as $key=>$quote){
                $img_url = $this->controller->file_url_of('quote_img',$quote['image'],'master');

                $quote['img_url'] = $img_url."?cache=".get_config('cash_version');
                $quote_html = $this->proccess_quote_html($quote_user['custom_html'],$quote);
                $quote['html'] = $this->proccess_quote_html($quote_html,$quote_user_procces_values);
                $quote_list[$key] = $quote;
            }

            $info = array(
                'quote_user'=>$quote_user,
                'list'=>$quote_list
            );
            $this->include_view('quotes/print_user.php',$info);
        }

        protected function clean_browser_type_html($html, $action_data){
            
            $browser_type_remove = "mobile";
            if(is_mobile()){
                $browser_type_remove = "desktop";               
            }
            elseif(isset($action_data['mobile']) && $action_data['mobile'] == 'yes'){
                $browser_type_remove = "desktop";
            }
            $html_arr = explode("<!--".$browser_type_remove."_only-->",$html);
            $return_html = "";
            foreach($html_arr as $html_part){
                $html_part_arr = explode("<!--end_".$browser_type_remove."_only-->",$html_part);
                if(!isset($html_part_arr[1])){
                    $return_html .= $html_part_arr[0];
                }
                else{
                    $return_html .= $html_part_arr[1];
                }
            }
            return $return_html;	
        }


        protected function proccess_quote_html($html,$quote){
            $html_final = "";
            $html_arr = explode("<!-- if",$html);
            foreach($html_arr as $html_part){
                $html_part_arr = explode("<!-- endif -->",$html_part);
                if(isset($html_part_arr[1])){	
                    $condition = $html_part_arr[0];
                    $html_part_0 = "";
                    $html_part_1 = $html_part_arr[1];

                    $condition_arr = explode("-->",$condition);
                    $condition_term = trim($condition_arr[0]);
                    $condition_term_not = str_replace("!","",$condition_term);

                    $condition_str = $condition_arr[1];
                    if($quote[$condition_term_not]){
                        if($condition_term_not == $condition_term){
                            $html_part_0 = $condition_str;
                        }
                    }
                    elseif($condition_term_not != $condition_term){      
                        $html_part_0 = $condition_str;
                    }
                    $html_final.=$html_part_0;
                    $html_final.=$html_part_1;
                }
                else{
                    $html_final.=$html_part;
                }
            }
            foreach($quote as $search=>$replace){
                if(is_null($replace)){
                    $replace = "";
                }
                $html_final = str_replace("{{".$search."}}",$replace,$html_final);
            }
            return $html_final;
        }
	}
?>