<?php


	class Net_bannersModule extends Module{
        
        public $add_models = array("biz_categories","siteBiz_forms","siteNet_banners");
        public function add_leftbar_banners(){
           
            $this->controller->add_asset_mapping(siteNet_banners::$assets_mapping);
            
            $biz_form_data = siteBiz_forms::get_current_biz_form();
            if(!$biz_form_data){
                return;
            }
            if($biz_form_data['cat_id'] == ""){
                return;
            }
            $cat_tree = Biz_categories::get_item_parents_tree($biz_form_data['cat_id'],'id, parent');
            $net_banners_arr = SiteNet_banners::get_cat_tree_net_banners($cat_tree);
            if(!$net_banners_arr){
                return;
            }
            $info = array(
                'form'=>$biz_form_data,
                'cat_tree'=>$cat_tree,
                'net_banners'=>$net_banners_arr
            );

            $this->include_view('net_banners/leftbar_net_banners.php',$info);
        }

	}
?>