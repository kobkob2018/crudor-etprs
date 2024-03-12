<?php
  class Gallery_imagesController extends CrudController{
    public $add_models = array("gallery_images","gallery","gallery_cat","gallery_cat_assign");

    protected function handle_access($action){
        
        switch ($action){
            case 'status_update':
                return $this->call_module('admin','handle_access_user_is','admin');
            case 'ajax_assign_user':
                return $this->call_module('admin','handle_access_site_user_is','master_admin');
                break;
            default:
                return $this->call_module('admin','handle_access_user_can','gallery');
                break; 
        }
    }

    protected function init_setup($action){
        if($action != 'gallery_list' && $action!='delete_gallery_cat'  && $action!='ajax_assign_user'){
            $gallery_id = $this->add_gallery_info_data();
            if(!$gallery_id){
                $this->redirect_to(inner_url("gallery_images/gallery_list/"));
                return false;
            }
        }
        return parent::init_setup($action);
    }

    public function gallery_list(){
        $payload = array(
            'order_by'=>'label'
        );
        $filter_arr = array('site_id'=>$this->data['work_on_site']['id']);
        $user_is_admin = $this->view->site_user_is('admin');
        if(!$user_is_admin){
          $filter_arr['user_id'] = $this->user['id'];
        }
        $list_info = $this->get_paginated_list_info($filter_arr);
        print_r_help($list_info);
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

        $item_list = Gallery::get_list($filter_arr,"*", $payload);
        $gallery_cats = Gallery_cat::get_list(array('site_id'=>$this->data['work_on_site']['id']));
        foreach($list_info['list'] as $item_key=>$item){
            $item['cat_assign_checkbox_list'] = $this->get_cat_assign_checkbox_list($item['id'],$gallery_cats);
            $list_info['list'][$item_key] = $item;
        }
        $list_info['list'] = $this->prepare_forms_for_all_list($list_info['list'],Gallery::setup_field_collection(),"gallery_");

        if($this->view->site_user_is('admin')){
            $this->data['gallery_cats'] = $this->prepare_forms_for_all_list($gallery_cats,Gallery_cat::setup_field_collection(),"cat_");
        }
        $list_info['site_users'] = $this->call_module("user_sites","get_site_users_list_for_item_assign");

        $this->include_view('gallery_images/gallery_list.php',$list_info);
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
        $return_arr = array(
          'success'=>'ok',
          'item_id'=>$item_id,
          'user_id'=>$user_info['id'],
          'user_label'=>$user_info['full_name']
        );
        Gallery::update($item_id,$update_arr);
        return print(json_encode($return_arr));
      }

    protected function get_filter_fields_collection(){
        $filter_fields_collection = array(        
          'free_search'=>array(
              'label'=>'חיפוש חפשי',
              'type'=>'text',
              'validation'=>'required',
              'filter_type'=>'like',
              'columns_like'=>array('label'),
          ), 
          'get_pending_pages'=>array(
            'label'=>'הצג גלריות',
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
  
      protected function feed_list_filter_with_field($filter_arr,$param_key, $field, $value){
        return parent::feed_list_filter_with_field($filter_arr,$param_key, $field, $value);
      }
  
      protected function get_paginated_list($filter_arr, $payload){
        $payload['order_by'] = "label, id";
        return Gallery::get_list($filter_arr, '*',$payload);
      }


    protected function get_cat_assign_checkbox_list($gallery_id,$gallery_cats){
        $cats_assigned = Gallery_cat_assign::get_gallery_assigned_cats($gallery_id);
        
        $return_arr = array();

        $gallery_cats[] = array('id'=>'0','label'=>'הצג כשאין קטגוריה');
        foreach($gallery_cats as $cat){
            $cat = array(
                'id'=>$cat['id'],
                'label'=>$cat['label'],
                'checked'=>false,
                'checked_str'=>''
            );
            if(in_array($cat['id'],$cats_assigned)){
                $cat['checked'] = true;
                $cat['checked_str'] = " checked ";
            }
            $return_arr[$cat['id']] = $cat;
        }
        return $return_arr;
    }

    public function create_gallerySend(){
        $fields_collection = Gallery::setup_field_collection();
        $form_handler = $this->init_form_handler("new_gallery");
        $form_handler->update_fields_collection($fields_collection);
        $validate_result = $form_handler->validate();
        if($validate_result['success']){
            $fixed_values = $validate_result['fixed_values'];
            $fixed_values['site_id'] = $this->data['work_on_site']['id'];
            $fixed_values['user_id'] = $this->user['id'];
            if(!$this->view->site_user_is('admin')){
                $fixed_values['status'] = '5';
            }
            $row_id = Gallery::create($fixed_values);
            SystemMessages::add_success_message("הגלרייה נוצרה בהצלחה");
        }
        else{
            if(!empty($validate_result['err_messages'])){
                foreach($validate_result['err_messages'] as $err_message){
                    SystemMessages::add_err_message($err_message);
                }
            }
        }
        $this->eject_redirect();
    }

    public function create_gallery_catSend(){ 
        if(!isset($_REQUEST['row'])){
            return $this->eject_redirect();
        }
        if(!isset($_REQUEST['row']['label'])){
            return $this->eject_redirect();
        }
        $label = $_REQUEST['row']['label'];
        if($label == ''){
            SystemMessages::add_err_message("תיקייה חייבת שם");
            return $this->eject_redirect();
        }
        $fixed_values = array(
            'label'=>$label,
            'site_id'=>$this->data['work_on_site']['id']
        );
        Gallery_cat::create($fixed_values);
        SystemMessages::add_success_message("התיקייה נוצרה");
        return $this->eject_redirect();
    }

    public function update_gallerySend(){
        $gallery_id = $_REQUEST['db_row_id'];

        $form_handler = $this->form_handlers["gallery_".$gallery_id];
    
        $validate_result = $form_handler->validate();
        $fixed_values = $validate_result['fixed_values'];
        
        if($validate_result['success']){
            Gallery::update($gallery_id,$fixed_values);
            $assign_cats = $_REQUEST['assign'];
            if(!is_array($assign_cats)){
                $assign_cats = array();
            }
            Gallery_cat_assign::assign_cats_to_gallery($gallery_id,$assign_cats);
            SystemMessages::add_success_message("הגלרייה עודכנה");
            
        }
        else{
            SystemMessages::add_err_message("שגיאה בעריכת הגלרייה");
            if(!empty($validate_result['err_messages'])){
              foreach($validate_result['err_messages'] as $message){
                SystemMessages::add_err_message($message);
              }
            }
        }
        $this->eject_redirect();

    }


    public function update_gallery_catSend(){
        $cat_id = $_REQUEST['db_row_id'];

        $form_handler = $this->form_handlers["cat_".$cat_id];
    
        $validate_result = $form_handler->validate();
        $fixed_values = $validate_result['fixed_values'];
        
        if($validate_result['success']){
            Gallery_cat::update($cat_id,$fixed_values);
            SystemMessages::add_success_message("התיקייה עודכנה");
            
        }
        else{
            SystemMessages::add_err_message("שגיאה בעריכת התיקייה");
            if(!empty($validate_result['err_messages'])){
              foreach($validate_result['err_messages'] as $message){
                SystemMessages::add_err_message($message);
              }
            }
        }
        $this->eject_redirect();

    }

    public function status_update(){
        
        if(!(isset($_REQUEST['status']) && isset($_REQUEST['row_id']))){
            SystemMessages::add_err_message("חסר מידע לפעולה");
            return $this->redirect_to(inner_url('gallery_images/gallery_list/'));
        }
        $row_id = $_REQUEST['row_id'];
        $status = $_REQUEST['status'];
        
        Gallery::update($row_id,array('status'=>$status));
        SystemMessages::add_success_message("סטטוס הפריט עודכן בהצלחה");
        return $this->redirect_to(inner_url('gallery_images/gallery_list/'));
    }
    
    public function delete_gallery(){
        $fields_collection = Gallery::setup_field_collection(Gallery::get_fields_collection_for_gallery_delete($this->data['gallery_info']['id'],$this->data['work_on_site']['id']));
        
        $form_handler = $this->init_form_handler("main");
        $form_handler->update_fields_collection($fields_collection);
        
        $this->add_form_builder_data($fields_collection,'delete_gallery_confirm',$this->data['gallery_info']['id']);  
        $this->include_view('gallery_images/gallery_delete_form.php');
    }

    public function delete_gallery_confirm(){
        $move_to_gallery = $_REQUEST['row']['move_images_to'];
        Gallery::move_images_from_gallery_to($this->data['gallery_info']['id'],$move_to_gallery);
        Gallery::delete($this->data['gallery_info']['id']);
        SystemMessages::add_success_message("התיקייה נמחקה");
        return $this->eject_redirect();
    }  
 
    
    public function delete_gallery_cat(){
        $cat_id = $_REQUEST['cat_id'];
        Gallery_cat::delete($cat_id);
        SystemMessages::add_success_message("התיקייה נמחקה");
        return $this->eject_redirect();
    }  
    

    public function list(){
        //if(session__isset())
        $fields_collection = Gallery_images::setup_field_collection();
        $filter_arr = $this->get_base_filter();
        $payload = array(
            'order_by'=>'priority'
        );
        $images_list = Gallery_images::get_list($filter_arr,"*", $payload);
        $this->data['images_list'] = $this->prepare_forms_for_all_list($images_list,Gallery_images::setup_field_collection(),"gallery_image_");
        $this->include_view('gallery_images/list.php');
    }

    protected function add_gallery_info_data(){
        if(!isset($_GET['gallery_id'])){
            global $action;
            if($action == 'status_update'){
                return $_GET['row_id'];
            }
            return false;
        }
        $gallery_id = $_GET['gallery_id'];
        $gallery_info = Gallery::get_by_id($gallery_id, 'id, label');
        $this->data['gallery_info'] = $gallery_info;
        if($gallery_info && isset($gallery_info['id'])){
            return $gallery_info['id'];
        }
    }

    protected function get_base_filter(){
        $gallery_id = $this->add_gallery_info_data();
        if(!$gallery_id){
            return;
        }

        $filter_arr = array(
            'gallery_id'=>$gallery_id,
    
        );  
        return $filter_arr;     
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
        $this->include_view('gallery_images/edit.php');
    }

    public function include_add_view(){
        $this->include_view('gallery_images/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("התמונה עודכנה בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("התמונה נוספה בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("התמונה נמחקה");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחרה תמונה");
    }   

    protected function delete_item($row_id){
      return Gallery_images::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Gallery_images::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('gallery_images/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("gallery_images/edit/?gallery_id=".$this->data['gallery_info']['id']."&row_id=".$item_info['id']);
    }

    protected function after_delete_redirect(){
        return $this->redirect_to(inner_url("/gallery_images/list/?gallery_id=".$this->data['gallery_info']['id']));
    }

    protected function after_add_redirect($item_id){
        return $this->redirect_to(inner_url("/gallery_images/list/?gallery_id=".$this->data['gallery_info']['id']));
    }

    protected function after_edit_redirect($item_id){
        return $this->redirect_to(current_url());
    }

    public function delete_url($item_info){
        return inner_url("gallery_images/delete/?gallery_id=".$this->data['gallery_info']['id']."&row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
        $fields_collection = Gallery_images::$fields_collection;
        if(isset($fields_collection['cat_id'])&& isset($this->data['cat_info'])){
            $fields_collection['cat_id']['default'] = $this->data['cat_info']['id'];
        }
        return Gallery_images::setup_field_collection($fields_collection);
    }

    protected function update_item($item_id,$update_values){
      return Gallery_images::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        $work_on_site = Sites::get_user_workon_site();
        $site_id = $work_on_site['id'];
        $fixed_values['site_id'] = $site_id;
        $fixed_values['user_id'] = $this->user['id'];
        $fixed_values['gallery_id'] = $this->data['gallery_info']['id'];
        return Gallery_images::create($fixed_values);
    }
  }
?>