<?php
  class ProductsController extends CrudController{
    public $add_models = array("products","product_cat");

    protected function handle_access($action){
        switch ($action){
            case 'status_update':
                return $this->call_module('admin','handle_access_user_is','admin');
                break;
            case 'ajax_assign_user':
                  return $this->call_module('admin','handle_access_site_user_is','master_admin');
                break;
            default:
                return $this->call_module('admin','handle_access_user_can','products');
                break; 
        }
    }

    protected function init_setup($action){
        return parent::init_setup($action);
    }  

    public function assign_subs(){
        $this->add_model("product_sub_assign");
        $this->add_model("product_sub");
        
        if(!isset($_GET['row_id'])){
            return $this->eject_redirect();
        }
        $this->data['item_info'] = $this->get_item_info($_GET['row_id']);
        $this->data['product_info'] = $this->data['item_info'];
        if(isset($_REQUEST['submit_assign'])){
            
            $assign_subs = array();
            
            foreach($_REQUEST['assign'] as $sub){
                if($sub != '-1'){
                    $assign_subs[] = $sub;
                }
            }
            Product_sub_assign::assign_subs_to_item($this->data['item_info']['id'],  $assign_subs);      
            SystemMessages::add_success_message("התיקיות שוויכו בהצלחה");
            return $this->redirect_to(inner_url("products/assign_subs/?row_id=".$this->data['item_info']['id'])); 
        }
        $filter_arr = $this->get_base_filter();
        $sub_list = Product_sub::get_list($filter_arr,"id, label");
        $subs_assigned = Product_sub_assign::get_assigned_subs_to_item($this->data['item_info']['id']);
        $subs_checked_list = array();
        foreach($subs_assigned as $sub){
            $subs_checked_list[$sub['sub_id']] = '1';
        }
        $check_options = array();
        foreach($sub_list as $sub){
            $checked = "";
            if(isset($subs_checked_list[$sub['id']])){
                $checked = "checked";
            }
            $check_options[] = array('value'=>$sub['id'],'title'=>$sub['label'],'checked'=>$checked);
        }
        $info = array('options'=>$check_options);
        $this->include_view('products/sub_assign_form.php',$info);
    }

    public function list(){
        //if(session__isset())
        
        $filter_arr = $this->get_base_filter();
        $user_is_admin = $this->view->user_is('admin',Sites::get_user_workon_site());
        if(!$user_is_admin){
          $filter_arr['user_id'] = $this->user['id'];
        }

        //$product_list = Products::get_list($filter_arr,"*", $payload);     
        $list_info = $this->get_paginated_list_info($filter_arr);
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
          $list_info['site_users'] = $this->call_module("user_sites","get_site_users_list_for_item_assign");
      
        $this->include_view('products/list.php',$list_info);
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
      Products::update($item_id,$update_arr);
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
            'label'=>'הצג מוצרים',
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
        return Products::get_list($filter_arr, '*',$payload);
      }

    protected function get_base_filter(){
        $filter_arr = array(
            'site_id'=>$this->data['work_on_site']['id']
        );  
        return $filter_arr;      
    }

    public function status_update(){
        if(!(isset($_REQUEST['status']) && isset($_REQUEST['row_id']))){
            SystemMessages::add_err_message("חסר מידע לפעולה");
            return $this->redirect_to(inner_url('products/list/'));
        }
        $row_id = $_REQUEST['row_id'];
        $status = $_REQUEST['status'];
        Products::update($row_id,array('status'=>$status));
        SystemMessages::add_success_message("סטטוס הפריט עודכן בהצלחה");
        return $this->redirect_to(inner_url('products/list/'));
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
        $this->data['product_info'] = $this->data['item_info'];
        $this->include_view('products/edit.php');
    }

    public function include_add_view(){
        $this->include_view('products/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("המוצר עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("המוצר נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("המוצר נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר מוצר");
    }   

    protected function delete_item($row_id){
      return Products::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Products::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('products/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("products/edit/?row_id=".$item_info['id']);
    }

    protected function after_delete_redirect(){
        return $this->redirect_to(inner_url("/products/list/"));
    }

    public function delete_url($item_info){
        return inner_url("products/delete/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
        return Products::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Products::update($item_id,$update_values);
    }

    protected function create_item($fixed_values){
        $fixed_values['site_id'] = $this->data['work_on_site']['id'];
        $fixed_values['user_id'] = $this->user['id'];
        if(!$this->view->site_user_is('admin')){
            $fixed_values['status'] = '5';
        }
        return Products::create($fixed_values);
    }
  }
?>