<?php
	class ProductsModule extends Module{
        public $add_models = array("siteProducts");
        public function cubes(){
            $this->controller->add_asset_mapping(SiteProducts::$assets_mapping);
            $site_id = $this->controller->data['site']['id'];
            $product_list = siteProducts::get_product_list($site_id, false, false, false, "RAND()");
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