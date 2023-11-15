<?php
  class PagesController extends CrudController{
    public $add_models = array("sites","adminPages");
    public function error() {
      SystemMessages::add_err_message("Oops! seems like you are in the wrong place");
      $this->include_view('pages/error.php');
    }

    protected function init_setup($action){
      return parent::init_setup($action);
    }

    protected function handle_access($action){
      switch ($action){
        case 'error':
          return true;
          break;
        default:
          return $this->call_module('admin','handle_access_user_can','pages');
          break;
        
      }
    }

		public function list(){
      
      $filter_arr = array('site_id'=>$this->data['work_on_site']['id']);
      $user_id_admin = $this->view->user_is('admin',Sites::get_user_workon_site());
      if(!$user_id_admin){
        $filter_arr['user_id'] = $this->user['id'];
      }
      if(isset($_REQUEST['setup_status'])){
        $filter_arr['status'] = array('5','9');
      }
      $content_pages = AdminPages::get_list($filter_arr, 'id, status, user_id, title, link, visible, views, convertions, spam_convertions');
      if($user_id_admin){
        $users_by_id = array();
        foreach($content_pages as $key=>$page){
          if(!isset($users_by_id[$page['user_id']])){
            $users_by_id[$page['user_id']] = Users::get_by_id($page['user_id'],'id, full_name, biz_name');
          }
          $page_user = $users_by_id[$page['user_id']];
          $page['user'] = $page_user;
          $page['user_label'] = "no-user-found";
          if($page_user){
            $page['user_label'] = $page_user['full_name'];
          }
          $content_pages[$key] = $page;
        }
      }
      $this->data['content_pages'] = $content_pages;

      if(session__isset('page_export_prepare')){
        $import_filter_arr = array('id'=>session__get('page_export_prepare'));
        $this->data['page_import_prepare'] = AdminPages::find($import_filter_arr, 'id, title, link');
      }

      $this->include_view('content_pages/list.php');

    }



    public function edit(){
      return parent::edit();
    }

    public function updateSend(){
      return parent::updateSend();
    }

    public function add(){
      return parent::add();
    }       

    public function createSend(){
      return parent::createSend();
    }

    public function delete(){
      return parent::delete();      
    }

    public function include_edit_view(){
      if(isset($this->data['item_info'])){
        $this->data['page_info'] = $this->data['item_info'];
      }
      $this->include_view('content_pages/edit.php');
    }

    public function include_add_view(){
      $this->include_view('content_pages/add.php');
    }   

    protected function update_success_message(){
      SystemMessages::add_success_message("הדף עודכן בהצלחה");

    }

    protected function create_success_message(){
      SystemMessages::add_success_message("הדף נוצר בהצלחה");

    }

    protected function delete_success_message(){
      SystemMessages::add_success_message("הדף נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר דף");
    }   

    protected function delete_item($row_id){
      return AdminPages::delete($row_id);
    }

    protected function get_item_info($row_id){
      return AdminPages::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('pages/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("pages/edit/?row_id=".$item_info['id']);
    }

    public function delete_url($item_info){
      return inner_url("pages/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return AdminPages::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return AdminPages::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){

      $work_on_site = Sites::get_user_workon_site();
      $site_id = $work_on_site['id'];
      $fixed_values['site_id'] = $site_id;
      $fixed_values['user_id'] = $this->user['id'];
      $fixed_values['link'] = str_replace(" ","-",$fixed_values['link']);
      if(!$this->view->site_user_is('admin')){
        $fixed_values['status'] = '5';
      }
      return AdminPages::create($fixed_values);
    }

    public function prepare_export(){
      $page_id = $_REQUEST['row_id'];
      session__set('page_export_prepare',$page_id);
      SystemMessages::add_success_message("הדף מוכן להעתקה באתר אחר");
      return $this->redirect_to(inner_url("pages/edit/?row_id=").$page_id);
    }

    public function page_import_unset(){
      session__unset('page_export_prepare');
      SystemMessages::add_success_message("הדף שוחרר מהעתקה");
      return $this->redirect_to(inner_url("pages/list/"));
    }
    
    public function import_page(){
      if(!session__isset('page_export_prepare')){
        SystemMessages::add_err_message("לא נבחר דף להעתקה");
        return $this->redirect_to(inner_url("pages/list/"));
      }
      $site_id = $this->data['work_on_site']['id'];
      $duplicate_page_id = session__get('page_export_prepare');
      $duplicate_page = AdminPages::get_by_id($duplicate_page_id);
      if(!$duplicate_page){
        SystemMessages::add_err_message("לא נבחר דף להעתקה");
        return $this->redirect_to(inner_url("pages/list/"));
      }
      $duplicate_page_filter = array(
        'site_id'=>$duplicate_page['site_id'],
        'page_id'=>$duplicate_page_id
      );

      $duplicate_page['site_id'] = $site_id;
      unset($duplicate_page['id']);
      $new_page_id = AdminPages::create($duplicate_page);

      $this->add_model("page_style");
      $this->add_model("biz_forms");
      $this->add_model("adminBlocks");

      $duplicte_blocks = AdminBlocks::get_list($duplicate_page_filter);
      if($duplicte_blocks){
        foreach($duplicte_blocks as $duplicate_block){
          $duplicate_block['site_id'] = $site_id;
          $duplicate_block['page_id'] = $new_page_id;
          unset($duplicate_block['id']);
          AdminBlocks::create($duplicate_block);
        }
      }
      $page_style_duplicate = Page_style::find($duplicate_page_filter);

      if($page_style_duplicate){
        $page_style_duplicate['site_id'] = $site_id;
        $page_style_duplicate['page_id'] = $new_page_id;
        unset($page_style_duplicate['id']);
        Page_style::create($page_style_duplicate);
      }
    
      $this->add_model("biz_forms");

      $biz_form_duplicate = Biz_forms::find($duplicate_page_filter);
      if($biz_form_duplicate){
        $biz_form_duplicate['site_id'] = $site_id;
        $biz_form_duplicate['page_id'] = $new_page_id;
        unset($biz_form_duplicate['id']);
        Biz_forms::create($biz_form_duplicate);
      }
      SystemMessages::add_success_message("הדף שוכפל בהצלחה");
      session__unset("page_export_prepare");
      return $this->redirect_to(inner_url("pages/edit/?row_id=".$new_page_id));

    }

  }
?>