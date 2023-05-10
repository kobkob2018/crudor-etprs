<?php
	class scrolling_last_requestsModule extends Module{
        
        public $add_models = array("biz_categories","siteBiz_forms");
        public function print(){

            $biz_form_data = siteBiz_forms::get_current_biz_form();
            if(!$biz_form_data){
                return;
            }
            if($biz_form_data['cat_id'] == ""){
                return;
            }
            $cat_tree = Biz_categories::get_item_parents_tree($biz_form_data['cat_id'],'id, parent');
            $last_requests = SiteBiz_requests::get_cat_last_requests($cat_tree);
            if($last_requests){

                //add data here
            }
            if(empty($last_requests)){
                return;
            }
            $info = array(
                'form'=>$biz_form_data,
                'cat_tree'=>$cat_tree,
                'last_requests'=>$last_requests
            );

            $this->include_view('supplier_cubes/leftbar_supplier_cubes.php',$info);
        }

	}
?>