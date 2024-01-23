<?php
  class PagesController extends CrudController{
    public $add_models = array("sites","adminPages");

    protected $session_order_by = "title";
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
        case 'ajax_assign_user':
            return $this->call_module('admin','handle_access_site_user_is','master_admin');
            break;
        case 'status_update':
          return $this->call_module('admin','handle_access_site_user_is','admin');
          break;
        default:
          return $this->call_module('admin','handle_access_user_can','pages');
          break;
        
      }
    }

		public function list(){
      
      $filter_arr = array('site_id'=>$this->data['work_on_site']['id']);

      $user_is_admin = $this->view->user_is('admin',Sites::get_user_workon_site());
      if(!$user_is_admin){
        $filter_arr['user_id'] = $this->user['id'];
      }

      $list_info = $this->get_paginated_list_info($filter_arr,array('page_limit'=>'300'));

      if($user_is_admin){
        $users_by_id = array();
        foreach($list_info['list'] as $key=>$page){
          if(!isset($users_by_id[$page['user_id']])){
            $users_by_id[$page['user_id']] = Users::get_by_id($page['user_id'],'id, full_name, biz_name');
          }
          $page_user = $users_by_id[$page['user_id']];
          $page['user'] = $page_user;
          $page['user_label'] = "no-user-found";
          if($page_user){
            $page['user_label'] = $page_user['full_name'];
          }
          $list_info['list'][$key] = $page;
        }
      }

      if(session__isset('page_export_prepare')){
        $import_filter_arr = array('id'=>session__get('page_export_prepare'));
        $this->data['page_import_prepare'] = AdminPages::find($import_filter_arr, 'id, title, link');
      }

      $list_info['site_users'] = $this->call_module("user_sites","get_site_users_list_for_item_assign");
      
      $this->include_view('content_pages/list.php',$list_info);

    }

    protected function get_filter_fields_collection(){
      $filter_fields_collection = array(  
        
        'archived'=>array(
          'label'=>'חיפוש ב',
          'type'=>'select',
          'default'=>'0',
          'options'=>array(
              array('value'=>'0', 'title'=>'דפים פעילים'),
              array('value'=>'1', 'title'=>'ארכיון')
          ),
      ), 
        'free_search'=>array(
            'label'=>'חיפוש חפשי',
            'type'=>'text',
            'filter_type'=>'like',
            'columns_like'=>array('title','link','description'),
        ), 
        'user_id_free'=>array(
          'label'=>'לפי שם משתמש',
          'type'=>'text',
          'filter_type'=>'method',
          'method'=>'find_users_by_string',
          'handle_access'=>array('method'=>'check_if_site_user_is','value'=>'admin')
      ), 
        'get_pending_pages'=>array(
          'label'=>'הצג עמודים',
          'type'=>'select',
          'default'=>'0',
          'options'=>array(
              array('value'=>'0', 'title'=>'הכל'),
              array('value'=>'1', 'title'=>'ממתינים לאישור')
          ),
          'filter_type'=>'constant',
          'constatnt'=>array('status'=>array('5','9')),
          'handle_access'=>array('method'=>'check_if_site_user_is','value'=>'admin')
        ), 
      );
      return $filter_fields_collection;
    }

    public function find_users_by_string($str){
      $users_find = Users::get_list(array(
        'free_search'=>array(
          'str_like'=>$str, 
          'columns_like'=>array('biz_name','full_name'))
      ),'id');

      $user_id_in_arr = array('-1');
      if($users_find){
        foreach($users_find as $user_found){
          $user_id_in_arr[] = $user_found['id'];
        }
      }
      return array(
        'key'=>'user_id',
        'value'=>$user_id_in_arr
      );
    }

    protected function feed_list_filter_with_field($filter_arr,$param_key, $field, $value){
      return parent::feed_list_filter_with_field($filter_arr,$param_key, $field, $value);
    }

    protected function get_paginated_list($filter_arr, $payload){
      $payload['order_by'] = $this->session_order_by.", id";
      return AdminPages::get_list($filter_arr, 'id, archived, status, user_id, title, link, visible, views, convertions, spam_convertions',$payload);
    }

    public function status_update(){
      if(!(isset($_REQUEST['status']) && isset($_REQUEST['row_id']))){
        SystemMessages::add_err_message("חסר מידע לפעולה");
        return $this->redirect_to(inner_url('pages/list/'));
      }
      $row_id = $_REQUEST['row_id'];
      $status = $_REQUEST['status'];
      AdminPages::update($row_id,array('status'=>$status));
      SystemMessages::add_success_message("סטטוס הפריט עודכן בהצלחה");
      return $this->redirect_to(inner_url('pages/list/'));
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

    public function ajax_assign_user(){
      $this->set_layout('blank');
      $return_arr = array("success"=>false,"err"=>'missing_params',"err_msg"=>__tr("Missing params"));
      if(!(isset($_REQUEST['item_id']) && isset($_REQUEST['user_id']))){
        return print(json_encode($return_arr));
      }
      $user_id = $_REQUEST['user_id'];
      $item_id = $_REQUEST['item_id'];
      $user_info = Users::get_by_id($user_id,"id, full_name");
      if(!$user_info){
        return print(json_encode($return_arr));
      }
      $update_arr = array(
        'user_id'=>$user_id
      );
      AdminPages::update($item_id,$update_arr);
      
      $return_arr = array(
        'success'=>'ok',
        'item_id'=>$item_id,
        'user_id'=>$user_info['id'],
        'user_label'=>$user_info['full_name']
      );
      return print(json_encode($return_arr));
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

      $fixed_values['site_id'] = $this->data['work_on_site']['id'];
      $fixed_values['user_id'] = $this->user['id'];
      $fixed_values['link'] = str_replace(" ","-",$fixed_values['link']);
      if(!$this->view->site_user_is('admin')){
        $fixed_values['status'] = '5';
      }
      $page_id = AdminPages::create($fixed_values);
      if(!$this->view->site_user_is('admin')){
        $this->create_portal_style_setup($page_id);
      }
      return $page_id;
    }

    protected function create_portal_style_setup($page_id){
      $this->add_model('page_style');
      $fixed_values = array();
      $fixed_values['site_id'] = $this->data['work_on_site']['id'];
      $fixed_values['user_id'] = $this->user['id'];
      $fixed_values['page_id'] = $page_id;
      $fixed_values['page_layout'] = '3';
      return Page_style::create($fixed_values);
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