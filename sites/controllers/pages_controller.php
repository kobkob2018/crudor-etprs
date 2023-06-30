<?php
  class PagesController extends CrudController{
    public $add_models = array("sitePages","siteBlocks","sitePage_style");
    public function error() {
      header('HTTP/1.0 404 Not Found');
      $this->include_view('pages/error.php');
    }

    protected function init_setup($action){
      return parent::init_setup($action);
    }

    protected function page_view(){
      if(!isset($this->data['is_home_page'])){
        $this->data['is_home_page'] = false;
      }
      $page = SitePages::get_current_page();
      
      $this->add_asset_mapping(SitePages::$assets_mapping);
      if(!$page){
        return $this->error();
      }
      $this->data['page'] = $page;
      //$this->data['page_meta_title'] = $page['meta_title'];

      $this->data['content_blocks'] = SiteBlocks::get_current_page_blocks();
      $page_style = SitePage_style::get_current_page_style();
      $this->data['page_style'] = $page_style;
      $this->data['page_layout'] = '0';
      if($page_style){
        $this->data['page_layout'] = $page_style['page_layout'];
        if($page_style['page_layout'] == '1'){
          $this->set_layout('landing_layout');
          $this->set_body('landing_body');
        }

        if($page_style['page_layout'] == '2'){
          $this->set_body('combine_body');
        }
      }

      if($page['meta_title'] != ""){
        $this->add_data("page_meta_title",$page['meta_title']);
      }
      if($page['meta_keywords'] != ""){
        $this->add_data("page_meta_keywords",$page['meta_keywords']);
      }
      if($page['meta_description'] != ""){
        $this->add_data("page_meta_description",$page['meta_description']);
      }
      if($page['right_banner'] != ""){
        $ogimage_url = $this->file_url_of('right_banner',$page['right_banner']);
        $this->add_data("page_meta_ogimage",outer_url($ogimage_url));
      }

      $this->include_view('pages/page_view.php');
    }

    protected function home(){
      $this->data['is_home_page'] = true;
      return $this->page_view();
    }

  }


?>