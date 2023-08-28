<?php


	class Supplier_cubesModule extends Module{
        
        public $add_models = array("biz_categories","siteSupplier_cubes","siteBiz_forms","siteNet_banners");
        public function add_leftbar_cubes(){
           
            $this->controller->add_asset_mapping(siteNet_banners::$assets_mapping);
            $this->controller->add_asset_mapping(SiteSupplier_cubes::$assets_mapping);
            $biz_form_data = siteBiz_forms::get_current_biz_form();
            if(!$biz_form_data){
                return;
            }
            if($biz_form_data['cat_id'] == ""){
                return;
            }
            
            $supplier_cubes = SiteSupplier_cubes::get_cat_supplier_cubes($biz_form_data['cat_id']);
            if($supplier_cubes){

                foreach($supplier_cubes as $cube_key=>$cube){
                    $cube['banner'] = false;
                    if($cube['banner_id'] != ""){
                        $banner = siteNet_banners::get_by_id($cube['banner_id']);
                        $cube['banner'] = $banner;
                    }
                    $supplier_cubes[$cube_key] = $cube;
                }
            }
            if(empty($supplier_cubes)){
                return;
            }
            $info = array(
                'form'=>$biz_form_data,
                'cat_tree'=>$cat_tree,
                'supplier_cubes'=>$supplier_cubes
            );

            $this->include_view('supplier_cubes/leftbar_supplier_cubes.php',$info);
        }

	}
?>