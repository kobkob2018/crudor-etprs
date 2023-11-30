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

        public function user_cubes(){
            
            $this->controller->add_asset_mapping(SiteProducts::$assets_mapping);
            $user_id = $this->controller->data['page']['user_id'];
            $action_data = $this->decode_action_data_arr();
            $limit = '6';
            $order_by = 'rand()';
            if(isset($action_data['limit'])){
                $limit = trim($action_data['limit']);
            }
            if(isset($action_data['order_by'])){
                $limit = trim($action_data['order_by']);
            }
            $user_products = SiteProducts::get_list(array('status'=>'1','user_id'=>$user_id),'*',array('limit'=>$limit, 'order_by'=>$order_by));
            $info = array(
                'product_list'=>$user_products
            );
            $this->include_view('products/cubes.php', $info);
        }
	}
?>