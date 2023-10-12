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

        if(isset($_REQUEST['page'])){
          $check_retirect = $this->check_link_redirect($_REQUEST['page']);
          if($check_retirect){
            return;
          }
        }
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
      if(isset($_GET['m'])){
        $check_redirect = $this->check_redirect();
        if($check_redirect){
          return;
        }
      }
      $this->data['is_home_page'] = true;
      return $this->page_view();
    }

    protected function check_link_redirect($link){
      $this->add_model('siteRedirections');
      $filter_arr = array(
        'site_id'=>$this->data['site']['id'],
        'm_param'=>'link',
        'id_param'=>'link',
        'item_id'=>$link
      );
      $redirection = SiteRedirections::find($filter_arr,'url, label');
      if($redirection){
        $found_redirection = $redirection['url'];
        header("Location: $found_redirection", true, 301);
        return true;
      }
    }

    protected function check_redirect(){
      $this->add_model('siteRedirections');
      $main_param = $_GET['m'];
      
      $options_arr = array(
        's.pr'=>array(
          'sub','cat','ud'
        ),
        'products'=>array(
          'sub','cat'
        ),
        'pr'=>array(
          'sub'
        ),
        'ga'=>array(
          'sub','cat'
        ),
        'landing'=>array(
          'ld'
        ),
      );

      $auto_to_home_main_params = array('landing');
      
      if(!isset($options_arr[$main_param])){
        return $this->check_migration_redirect();
      }
       
      $found_redirection = false;
      foreach($options_arr[$main_param] as $id_param){
        if(!isset($_GET[$id_param]) || $_GET[$id_param] == ""){
          continue;
        }
        
        $item_id = $_GET[$id_param];
        $filter_arr = array(
          'site_id'=>$this->data['site']['id'],
          'm_param'=>$main_param,
          'id_param'=>$id_param,
          'item_id'=>$item_id
        );
        $redirection = SiteRedirections::find($filter_arr,'url, label');
        if($redirection){
          $found_redirection = $redirection['url'];
        }
        else{
          
        }
      }
      if($found_redirection){
        header("Location: $found_redirection", true, 301);
        return true;
      }
      elseif(in_array($main_param,$auto_to_home_main_params)){
        $home_url = outer_url();
        header("Location: $home_url", true, 301);
        return true;
      }

      return $this->check_migration_redirect();
    }

    protected function check_migration_redirect(){
      $main_param = $_GET['m'];

      $options_arr = array(
        's.pr'=>'products',
        'products'=>'products',
        'pr'=>'products',
        'ga'=>'galleries',
        'text'=>'content_pages'
      );

      if(!isset($options_arr[$main_param])){
        return $this->check_migration_redirect();
      }

      $migration_arr = array(
        'products'=>array(
          'url'=>'products/view/',
          'url_method'=>'add_migration_url_params',
          'params'=>array(
            'sub'=>array(
              'migration_table'=>'migration_product_cat',
              'item_id_param'=>'cat_id',
              'item_url_param'=>'cat'
            ),
            'cat'=>array(
              'migration_table'=>'migration_product_sub',
              'item_id_param'=>'sub_id',
              'item_url_param'=>'sub'
            ),
            'ud'=>array(
              'migration_table'=>'migration_product',
              'item_id_param'=>'product_id',
              'item_url_param'=>'p'
            )
          ),
        ),
        'galleries'=>array(
          'url'=>'gallery/view/',
          'url_method'=>'add_migration_url_params',
          'params'=>array(
            'sub'=>array(
              'migration_table'=>'migration_gallery_cat',
              'item_id_param'=>'cat_id',
              'item_url_param'=>'cat'
            ),
            'cat'=>array(
              'migration_table'=>'migration_gallery',
              'item_id_param'=>'gallery_id',
              'item_url_param'=>'g'
            ),
          ),
        ),
        'content_pages'=>array(
          'url_method'=>'find_content_page_url',
          'params'=>array(
            't'=>array(
              'migration_table'=>'migration_page',
              'item_id_param'=>'page_id',
              'item_url_param'=>'p'
            ),
          ),
        ),
      );

      $migration_unit = $migration_arr[$options_arr[$main_param]];
      $url_params_arr = array();
      foreach($migration_unit['params'] as $unit_param_key=>$unit_param){
        $migration_item = false;
        if(isset($_GET[$unit_param_key])){
          $migration_item = TableModel::simple_find_by_table_name(array('old_id'=>$_GET[$unit_param_key]),$unit_param['migration_table'],$unit_param['item_id_param']);
        }
        if($migration_item){
          $url_params_arr[$unit_param['item_url_param']] = $migration_item[$unit_param['item_id_param']];
        }
      }

      if(empty($url_params_arr)){
        return false;
      }

      $redirect_url = $this->$migration_unit['redirect_method']($migration_unit,$url_params_arr);
      if($redirect_url){
        header("Location: $redirect_url", true, 301);
        return true;
      }

      return false;
    }

    protected function find_content_page_url($migration_unit,$url_params_arr){
      $page_id = $url_params_arr[$migration_unit['item_url_param']];
      $content_page = TableModel::simple_find_by_table_name(array('id'=>$page_id),'content_pages','link');
      if(!$content_page){
        return false;
      }
      return inner_url($content_page['link']);
    }

    protected function add_migration_url_params($migration_unit,$url_params_arr){
      $url_params_str_arr = array();
      foreach($url_params_arr as $key=>$val){
        $url_params_str_arr[] = "$key=$val";
      }
      $url_params_str = implode("&",$url_params_str_arr);
      $url = inner_url($migration_unit['url'])."?".$url_params_str;
      return $url;
    }


  }


?>