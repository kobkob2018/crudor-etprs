<?php
  class ProductsController extends CrudController{
    public $add_models = array("siteProducts");

    protected function init_setup($action){
        return parent::init_setup($action);
    }

    public function error() {
        header('HTTP/1.0 404 Not Found');
        $this->include_view('pages/error.php');
    }
    protected function view(){
        $site_id = $this->data['site']['id'];

        $product_id = false;
        $product = false;
        $more_products = false;
        $product_list = false;
        $product_images = false;
        if(isset($_REQUEST['p'])){
            $product_id = $_REQUEST['p'];
            if($product_id == ""){
                return $this->error();
            }
            $product = SiteProducts::get_by_id($product_id);
            $product_images = SiteProducts::get_product_images($product_id);  
            if(!$product){
                return $this->error();
            }
            $more_products = SiteProducts::get_more_products($product_id);

            if($product['meta_title'] != ""){
                $this->add_data("page_meta_title",$product['meta_title']);
            }
            if($product['meta_keywords'] != ""){
                $this->add_data("page_meta_keywords",$product['meta_keywords']);
            }
            if($product['meta_description'] != ""){
                $this->add_data("page_meta_description",$product['meta_description']);
            }
            if($product['image'] != ""){
                $ogimage_url = $this->file_url_of('product_image',$product['image']);
                $this->add_data("page_meta_ogimage",outer_url($ogimage_url));
            }
        }

        $this->add_asset_mapping(SiteProducts::$assets_mapping);

        $cat_list = SiteProducts::get_cat_list($site_id);

        $selected_cat = false;
        if(isset($_REQUEST['cat'])){
            $selected_cat = $_REQUEST['cat'];
        }
        foreach($cat_list as $key=>$cat){
            $selected_str = "";
            if($selected_cat == $cat['id']){
                $selected_str = " selected ";
            }
            $cat['selected_str'] = $selected_str;
            $cat_list[$key] = $cat;
        }

        $selected_sub = false;
        if(isset($_REQUEST['sub'])){
            $selected_sub = $_REQUEST['sub'];
        }

        $sub_list = false;
        if($selected_cat){
            $sub_list = SiteProducts::get_sub_list($site_id,$selected_cat);
            foreach($sub_list as $key=>$sub){
                $selected_str = "";
                if($selected_sub == $sub['id']){
                    $selected_str = " selected ";
                }
                $sub['selected_str'] = $selected_str;
                $sub_list[$key] = $sub;
            }
        }

        if(!$product){
            $product_list = SiteProducts::get_product_list($site_id,$selected_cat,$selected_sub);
        }

        $info = array(
            'product'=>$product,
            'images'=>$product_images,
            'more_products'=>$more_products,
            'cat_list'=>$cat_list,
            'sub_list'=>$sub_list,
            'selected_cat'=>$selected_cat,
            'selected_sub'=>$selected_sub,
            'product_list'=>$product_list
        );
        
        $this->include_view('products/view.php', $info);
        print_r_help($info);
    }

  }
?>