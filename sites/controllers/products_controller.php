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
        if(!isset($_REQUEST['p'])){
            return $this->error();
        }
        if($_REQUEST['p'] == ""){
            return $this->error();
        }
        $product_id = $_REQUEST['p'];

        $this->add_asset_mapping(SiteProducts::$assets_mapping);

        $product = SiteProducts::get_by_id($product_id);

        $product_images = SiteProducts::get_product_images($product_id);

        $more_products = SiteProducts::get_more_products($product_id);

        if(!$product){
            return $this->error();
        }

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

        $info = array(
            'product'=>$product,
            'images'=>$product_images,
            'more_products'=>$more_products
        );
        $this->include_view('products/view.php', $info);
    }

  }
?>