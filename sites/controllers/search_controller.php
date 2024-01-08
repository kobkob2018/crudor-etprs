<?php
  class SearchController extends CrudController{
    public $add_models = array("sitePages","siteProducts");

    protected function init_setup($action){
      $this->data['page_meta_title'] = $this->data['site']['meta_title'];
      return parent::init_setup($action);
    }

    protected function result(){
      if(!isset($_REQUEST['user_search'])){
        SystemMessages::add_err_message(__tr("Empty search"));
        return;
      }
      if($_REQUEST['user_search'] == ""){
        SystemMessages::add_err_message(__tr("Empty search"));
        return;
      }
      $search = $_REQUEST['user_search'];

      $this->add_asset_mapping(SitePages::$assets_mapping);
      $this->add_asset_mapping(SiteProducts::$assets_mapping);
      $pages_list = SitePages::search_by_str($search);
      if(!$pages_list){
        $pages_list = array();
      }

      $product_list = SiteProducts::search_by_str($search);
      if(!$product_list){
        $product_list = array();
      }

      foreach($product_list as $key=>$product){
        $product_list[$key]['url'] = inner_url("products/view/?p=".$product['id']);
      }

      $info = array(
          'search'=>$search,
          'pages_list'=>$pages_list,
          'product_list'=>$product_list
      );
      $this->include_view('search/list.php', $info);
    }

  }
?>