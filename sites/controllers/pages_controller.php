<?php
  class PagesController extends CrudController{
    public $add_models = array("sitePages","siteBlocks","sitePage_style");
    public function error() {
      SystemMessages::add_err_message("Oops! seems like you are in the wrong place");
      $this->include_view('pages/error.php');
    }

    protected function init_setup($action){
      $this->data['page_meta_title'] = $this->data['site']['title'];
      return parent::init_setup($action);
    }

    protected function page_view(){
      $page = SitePages::get_current_page();
      
      $this->add_asset_mapping(SitePages::$assets_mapping);
      if(!$page){
        return $this->error();
      }
      $this->data['page'] = $page;
      $this->data['page_meta_title'] = $page['meta_title'];

      $this->data['content_blocks'] = SiteBlocks::get_current_page_blocks();
      $page_style = SitePage_style::get_current_page_style();
      $this->data['page_style'] = $page_style;
      if($page_style){
        if($page_style['page_layout'] == '1'){
          $this->set_layout('landing_layout');
          $this->set_body('landing_body');
        }

        if($page_style['page_layout'] == '2'){
          $this->set_body('combine_body');
        }
      }

      $this->include_view('pages/page_view.php');
    }

    protected function home(){
      return $this->page_view();
    }
  //override function 
  // here we add the modules snippets!!
    public function print_body2(){
      ob_start();
      $this->include_view($this->action_params['body_layout_file']);
      $body_output = ob_get_clean();
      
      $body_output = $this->clean_mobile_conditions($body_output);
      $body_output = $this->setup_cat_phone_text($body_output);  
      $body_output = $this->setup_text_modules($body_output);

      print($body_output);
      /*
        on blank layout this is not called
      */ 
    }
  }


?>