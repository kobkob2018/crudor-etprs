<?php
	class ProductsModule extends Module{
        public $add_models = array("siteProducts");
        public function cubes(){
            $this->controller->add_asset_mapping(SiteProducts::$assets_mapping);
            $site_id = $this->controller->data['site']['id'];
            $action_data = $this->decode_action_data_arr();
            $limit = '4';
            if(isset($action_data['limit'])){
                $limit = trim($action_data['limit']);
            }
            $cat_id = false;
            if(isset($action_data['cat'])){
                $cat_id = trim($action_data['cat']);
            }
            $product_list = siteProducts::get_product_list($site_id, $cat_id, false, $limit, "RAND()");
            if(!$product_list){
                return;
            }
            $info = array(
                'product_list'=>$product_list
            );
            $this->include_view('products/cubes.php', $info);
        }
	}
?>