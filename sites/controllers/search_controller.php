<?php
  class SearchController extends CrudController{
    public $add_models = array("sitePages","siteProducts");

    protected function init_setup($action){
      $this->data['page_meta_title'] = $this->data['site']['meta_title'];
      return parent::init_setup($action);
    }

    protected function result(){
      if(!isset($_REQUEST['user_search'])){
        SystemMessages::add_err_message("חיפוש ריק");
        return;
      }
      if($_REQUEST['user_search'] == ""){
        SystemMessages::add_err_message("חיפוש ריק");
        return;
      }
      $search = $_REQUEST['user_search'];

      $this->add_asset_mapping(SitePages::$assets_mapping);
      $pages_list = SitePages::search_by_str($search);
      if(!$pages_list){
        $pages_list = array();
      }
      $info = array(
          'search'=>$search,
          'pages_list'=>$pages_list
      );
      $this->include_view('search/list.php', $info);
    }

  }
?>