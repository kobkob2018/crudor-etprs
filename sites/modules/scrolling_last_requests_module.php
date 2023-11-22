<?php
	class scrolling_last_requestsModule extends Module{
        
        public $add_models = array("biz_categories","cities","siteBiz_forms","siteBiz_requests");
        public function print(){

            $biz_form_data = siteBiz_forms::get_current_biz_form();
            if(!$biz_form_data){
                return;
            }
            
            //$last_requests = SiteBiz_requests::get_form_last_requests($biz_form_data['id']);

            $last_requests = SiteBiz_requests::get_cat_last_requests($biz_form_data['cat_id']);
            $cat_names = array();
            if($last_requests){
                foreach($last_requests  as $key=>$biz_request){
                    $cat_id = $biz_request['cat_id'];
                    if(!$cat_id){
                        continue;
                    }
                    if(!isset($cat_names[$cat_id])){
                        $cat_name = Biz_categories::get_by_id($cat_id,'label');
                        if(!$cat_name){
                            $cat_name = "";
                        }
                        $cat_names[$cat_id] = $cat_name['label'];
                    }
                    $city_id = $biz_request['city_id'];
                    $biz_request['cat_name'] = $cat_names[$cat_id];
                    if(!isset($city_names[$cat_id])){
                        $city_name = Cities::get_by_id($city_id,'label');
                        if(!$city_name){
                            $city_name = array("label"=>"");
                        }
                        $city_names[$city_id] = $city_name['label'];
                    }
                    $biz_request['city_name'] = $city_names[$city_id];
                    $last_requests[$key] = $biz_request;
                }
                //add data here
            }
            if(empty($last_requests)){
                return;
            }
            $info = array(
                'form'=>$biz_form_data,
                'last_requests'=>$last_requests
            );

            $this->include_view('scrolling/last_requests.php',$info);
        }

	}
?>