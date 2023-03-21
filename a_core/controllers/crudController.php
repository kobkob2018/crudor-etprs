<?php
  class CrudController extends Controller{

    protected $assets_map = array('static'=>'');
    protected function init_setup($action){
        $this->set_priority_from_session();
        return parent::init_setup($action);
    }

    public function edit(){
        $row_id = false;
        if(isset($_REQUEST['row_id'])){
            $row_id = $_REQUEST['row_id'];
        }
        elseif(isset($this->data['row_id'])){
            $row_id = $this->data['row_id'];
        }
        if(!$row_id){
            $this->row_error_message();
            return $this->eject_redirect();
        }

        $this->data['item_info'] = $this->get_item_info($row_id);




        if(!$this->data['item_info']){
            $this->row_error_message();
            return $this->eject_redirect();
        }
        $item_delete_url = $this->delete_url($this->data['item_info']);
        if($item_delete_url && $item_delete_url != ""){
            $this->data['item_delete_url'] = $item_delete_url;
        }
        
        $fields_collection = $this->get_fields_collection();

        $form_handler = $this->init_form_handler();
        $form_handler->update_fields_collection($fields_collection);
        $form_handler->setup_db_values($this->data['item_info']);

        if(isset($_REQUEST['remove_file'])){
            return $this->remove_file($fields_collection, $form_handler);
        }

        $this->send_action_proceed();

        $this->add_form_builder_data($fields_collection,'updateSend',$this->data['item_info']['id']);  
        $this->include_edit_view();
  
    }
  
    public function updateSend(){
        $row_id = false;
        if(isset($_REQUEST['db_row_id'])){
            $row_id = $_REQUEST['db_row_id'];
        }
        elseif(isset($this->data['db_row_id'])){
            $row_id = $this->data['db_row_id'];
        }
        if(!$row_id){
            return;
        }
        $form_handler = $this->init_form_handler();
        $validate_result = $form_handler->validate();
        $fixed_values = $validate_result['fixed_values'];
        foreach($fixed_values as $key=>$value){
            $fixed_values[$key] = str_replace('{{row_id}}',$row_id,$value);
        }
        $validate_result['fixed_values'] = $fixed_values;
        if($validate_result['success']){
            $this->update_item($row_id,$form_handler->fix_values_for_update($fixed_values));
            $files_result = $form_handler->upload_files($validate_result, $row_id);
            $this->update_success_message();
            $this->after_edit_redirect($this->data['item_info']);
        }
        else{
            if(!empty($validate_result['err_messages'])){
                $this->data['form_err_messages'] = $validate_result['err_messages'];
            }
        }
    }

    protected function remove_file($fields_collection, $form_handler){
        $item_info = $this->data['item_info'];
        $item_id = $item_info['id'];
        
        $field_key_for_file = $_REQUEST['remove_file'];
        if(isset($fields_collection[$field_key_for_file])){
            $form_handler->remove_file($field_key_for_file);
            $update_values = array($field_key_for_file=>'');
            $this->update_item($item_id,$update_values);
            SystemMessages::add_success_message("הקובץ הוסר");
            return $this->redirect_back_to_item($this->data['item_info']);
        }
    }

    public function add(){
        $fields_collection = $this->get_fields_collection();
        $form_handler = $this->init_form_handler();
        $form_handler->update_fields_collection($fields_collection);
  
        $this->send_action_proceed();
        $this->add_form_builder_data($fields_collection,'createSend','new');
        $this->send_action_proceed();
        $this->include_add_view();           
    }       
  
    public function file_master_url_of($field_name, $file_name){
        return $this->file_url_of($field_name, $file_name, 'master');

    }   

    public function file_url_of($field_name, $file_name, $relative_site = 'self'){
        $fileds_arr = $this->get_assets_mapping();
        if($fileds_arr && isset($fileds_arr[$field_name])){
            $file_dir = $fileds_arr[$field_name];
            $assets_dir = $this->get_assets_dir($relative_site);
            return $assets_dir['url'].$file_dir."/".$file_name;

        }
        return $file_name;

    }

    public function add_asset_mapping($mapping_arr){
        
        foreach($mapping_arr as $mapping_key=>$mapping){
            $this->assets_map[$mapping_key] = $mapping;
        }
    }

    protected function get_assets_mapping(){
        return $this->assets_map;
    }

    protected function add_form_builder_data($fields_collection, $sendAction, $row_id){
        global $controller;
        global $action;

        $form_builder_data = array(
            'controller'=>$controller,
            'action'=>$action
        );
        
        $enctype_str = '';
        foreach($fields_collection as $field){
            if($field['type'] == 'file'){
                $enctype_str = 'enctype="multipart/form-data"';
            }
        }
        $form_builder_data['enctype_str'] = $enctype_str;
        $form_builder_data['sendAction'] = $sendAction;
        $form_builder_data['db_row_id'] = $row_id;
        $form_builder_data['fields_collection'] = $fields_collection;
        $this->data['form_builder'] = $form_builder_data;
    }

    public function createSend(){

        $form_handler = $this->init_form_handler();
        $validate_result = $form_handler->validate();
        if($validate_result['success']){
            $fixed_values = $validate_result['fixed_values'];
            $row_id = $this->create_item($form_handler->fix_values_for_update($fixed_values));
  
            $fixed_row_values = array();
            foreach($fixed_values as $key=>$value){
                $fixed_row_value = str_replace('{{row_id}}',$row_id,$value);
                if($fixed_row_value != $value){
                    $fixed_row_values[$key] = $fixed_row_value;
                }
            }
            if(!empty($fixed_row_values)){
                $this->update_item($row_id,$fixed_row_values);
            }
            $validate_result['fixed_values'] = $fixed_row_values;
            $files_result = $form_handler->upload_files($validate_result, $row_id);
            $this->create_success_message();
            $this->after_add_redirect($row_id);
        }
        else{
            if(!empty($validate_result['err_messages'])){
                $this->data['form_err_messages'] = $validate_result['err_messages'];
            }
        }
    }

    public function delete(){

        if(!isset($_GET['row_id'])){
            $this->row_error_message();
            return $this->eject_redirect();
        }

        $this->data['item_info'] = $this->get_item_info($_GET['row_id']);
        if(!$this->data['item_info']){
            $this->row_error_message();
            return $this->eject_redirect();
        }

        $item_info = $this->data['item_info'];

        $fields_collection = $this->get_fields_collection();

        $this->delete_item_files($item_info,$fields_collection);

        $this->delete_item($this->data['item_info']['id']);
        $this->delete_success_message();
        return $this->after_delete_redirect();
    }

    protected function delete_item_files($item_info, $fields_collection){
        $form_handler = $this->init_form_handler();
        $form_handler->update_fields_collection($fields_collection);
        $form_handler->setup_db_values($item_info);

        if(is_array($fields_collection)){
            foreach($fields_collection as $field_key=>$field){
                if($field['type'] == 'file'){
                    $form_handler->remove_file($field_key);
                }
            }
        }
    }

    protected function set_priority(){
        global $controller;
        $session_param_name = $controller."_priority_set";
        $filter_arr = $this->get_base_filter();
        if(isset($_GET['row_id'])){
            $this->rearange_priority($filter_arr);
            session__set($session_param_name, $_GET['row_id']);
        }
        if(isset($_GET['cancel'])){
            session__unset($session_param_name);
        }
        if(isset($_GET['row_to'])){
            if(!isset($this->data['set_priority_item'])){
                return;
            }
            $priority = '0';
            $item_to_id = $_GET['row_to'];
            
            $place_priority = $this->get_priority_space($filter_arr, $item_to_id);
            if($place_priority){
                $priority = $place_priority;
            }
            
            
            $this->update_item($this->data['set_priority_item']['id'], array('priority'=>$priority));
            session__unset($session_param_name);
        }
        return $this->eject_redirect();
    }

    protected function set_priority_from_session(){
        global $controller;
        $session_param_name = $controller."_priority_set";
        if(session__isset($session_param_name)){
            $item_info = $this->get_item_info(session__get($session_param_name));
            $this->data['set_priority_item'] = $item_info;
        }
    }


    protected function move_item_prepare($item_identifier){
        global $controller;
        if($item_identifier == 'cancel'){
            session__unset($controller.'_item_to_move');
        }
        elseif($item_identifier == 'here'){
            $item_to_move_id = session__get($controller.'_item_to_move');
            $parent_id = 0;
            if(isset($_GET['row_id'])){
                $parent_id = $_GET['row_id'];
            }
            $this->get_item_parents_tree($parent_id,'id');
            $parent_tree = $this->get_item_parents_tree($parent_id,'id');
            foreach($parent_tree as $branch){
                if($item_to_move_id == $branch['id']){
                    SystemMessages::add_err_message("לא ניתן להעביר רכיב לצאצאיו");
                    return $this->eject_redirect();
                }
            }
            $this->update_item($item_to_move_id,array('parent'=>$parent_id));
            SystemMessages::add_success_message("הרכיב הועבר בהצלחה");
            session__unset($controller.'_item_to_move');
            return $this->redirect_back_to_item(array('id'=>$parent_id));
        }
        else{
            session__set($controller.'_item_to_move',$item_identifier);
        }
        return $this->eject_redirect();
    }

    protected function get_move_item_session(){
        global $controller;
        $session_param = $controller.'_item_to_move';
        if(session__isset($session_param)){
            $move_item_id = session__get($session_param);
            $move_menu_item_tree = $this->get_item_parents_tree($move_item_id,'id, label');
            $this->data['move_item'] = array(
                'item_id'=>$move_item_id,
                'tree'=>$move_menu_item_tree
            );
        }
    }

    public function get_label_value($value_identifier, $item, $fields_collection = false){
        if(!$fields_collection){
            if(isset($this->data['fields_collection'])){
                $fields_collection = $this->data['fields_collection'];
            }
        }
        if(!$fields_collection){
            return "";
        }
        if(!isset($fields_collection[$value_identifier])){
            return "";
        }
        if(!isset($item[$value_identifier])){
            return "";
        }

        $value = $item[$value_identifier];

        $field = $fields_collection[$value_identifier];

        if(!isset($field['option_labels'])){
            return "";
        }
        $option_labels = $field['option_labels'];
        if(!isset($option_labels[$value])){
            return "";
        }
        return $option_labels[$value];
    }

    public function setup_tree_select_info($assign_info){
        $this->data['assign_info'] = $assign_info;
        
        $row_id = false;
        if(isset($_REQUEST['row_id'])){
            $row_id = $_REQUEST['row_id'];
        }
        elseif(isset($this->data['row_id'])){
            $row_id = $this->data['row_id'];
        }
        if(!$row_id){
            $this->row_error_message();
            return $this->eject_redirect();
        }

        $this->data['item_info'] = $this->get_item_info($row_id);
        if(!$this->data['item_info']){
            $this->row_error_message();
            return $this->eject_redirect();
        }
        $get_item_assign_list_method_name = "get_item_assign_list_for_".$assign_info['alias'];
        $item_assign_arr = $this->$get_item_assign_list_method_name($row_id);
        $checked_assigns = array();
        foreach($item_assign_arr as $item_assign){
            $checked_assigns[$item_assign[$assign_info['assign_1']]] = true;
        }

        $add_is_checked_value = array(
            'controller'=>$this,
            'method'=>'add_assign_is_checked_param',
            'more_info'=>array(
                'item_assign_arr'=>$checked_assigns
            ),
        );

        $payload = array('add_custom_param'=>$add_is_checked_value);


        $get_offsprings_method_name = "get_assign_item_offsprings_tree_for_".$assign_info['alias'];
        $assign_tree_item = array(
            'id'=>'0',
            'checked'=>true,
            'label'=>'begin',
            'open_state'=>true,

            'children'=>$this->$get_offsprings_method_name($payload));



        $assign_tree_item = $this->add_has_checked_children_param($assign_tree_item);
        if(!isset($this->data['assign_trees'])){
            $this->data['assign_trees'] = array();
        }

        $this->data['assign_trees'][$assign_info['table']] = $assign_tree_item;
    }

    public function add_recursive_assign_select_view($assign_tree_item){

        $open_state_class = "closed";
        if($assign_tree_item['open_state']){
            $open_state_class = "open";
        }
        $assign_tree_item['open_class'] = $open_state_class;
        $this->include_view("tree/select_assigns_children.php",array('item'=>$assign_tree_item));
    }


    protected function add_has_checked_children_param($assign_tree_item, $count = 0){
        $count++;
        if($count>10){
            exit("count is $count");
        }
        $has_checked_children = false;
        $open_state = false;
        if($assign_tree_item['checked']){
            $open_state = true;
        }
        if($assign_tree_item['children']){
            foreach($assign_tree_item['children'] as $key=>$child_item){
                $child_item = $this->add_has_checked_children_param($child_item, $count);
                if($child_item['checked'] || $child_item['has_checked_children'] || $child_item['open_state']){
                    $has_checked_children = true;
                    $open_state = true;                
                }
                $assign_tree_item['children'][$key] = $child_item;
            }
        }
        $assign_tree_item['has_checked_children'] = $has_checked_children;
        $assign_tree_item['open_state'] = $open_state;
        return $assign_tree_item;
    }


    public function add_assign_is_checked_param($check_info, $more_info){
        $check_info['checked'] = '0';
        if(isset($more_info['item_assign_arr']) && is_array($more_info['item_assign_arr'])){
            if(isset($more_info['item_assign_arr'][$check_info['id']])){
                $check_info['checked'] = '1';
            }
        }
        return $check_info;
    }

    public function set_assignsSend(){
        $row_id = $_REQUEST['row_id'];
        $selected_assigns = array();
        if(isset($_REQUEST['assign'])){
            $selected_assigns = $_REQUEST['assign'];
        }

        $assign_to_item_method_name = "assign_to_item_for_".$this->data['assign_info']['alias'];

        $add_assign_success_message = "add_assign_success_message_for_".$this->data['assign_info']['alias'];

        $this->$assign_to_item_method_name($row_id, $selected_assigns);
        $this->$add_assign_success_message();
        $this->redirect_to(current_url());
    }


    public function setup_item_form_handler($item_key,$item,$field_collection){
        if(!$item || $item == null){
            return $item;
        }
        
        //setup form for specific item (like the parent item or the children items) children items
        $form_handler = $this->init_form_handler($item_key);
        $form_handler->update_fields_collection($field_collection);
        $form_handler->setup_db_values($item);
        $item['form_identifier'] = $item_key;
        return $item;
    }

    protected function prepare_forms_for_all_list($item_list, $field_collection = false, $prefix = "item_"){
        if(!$field_collection){
            $field_collection = $this->get_fields_collection();
        }
          
        $form_handler = $this->init_form_handler();
        $form_handler->update_fields_collection($field_collection);
    
        foreach($item_list as $item_key=>$item){
          $item_identifier = $prefix.$item['id'];
          //setup form for all children items
          $item = $this->setup_item_form_handler($item_identifier,$item,$field_collection);
          $item_list[$item_key] = $item;
        }
        return $item_list;
    }

    public function listUpdateSend($prefix = "item_"){
        if(!isset($_REQUEST['db_row_id'])){
          return;
        }
        $row_id = $_REQUEST['db_row_id'];
        $item_identifier = $prefix.$row_id;
        
        if(!isset($this->form_handlers[$item_identifier])){
            return;
        }
        $form_handler = $this->form_handlers[$item_identifier];
    
        $validate_result = $form_handler->validate();
        $fixed_values = $validate_result['fixed_values'];
        if($validate_result['success']){
            $this->update_item($row_id,$form_handler->fix_values_for_update($fixed_values));
            $this->update_success_message();
            
        }
        else{
          SystemMessages::add_err_message("שגיאה בעריכת הרכיב");
          if(!empty($validate_result['err_messages'])){
              foreach($validate_result['err_messages'] as $message){
                SystemMessages::add_err_message($message);
              }
          }
        }
        $this->redirect_to(current_url()); 
    }

    public function listCreateSend(){
        
        $form_handler = $this->init_form_handler();
    
        $validate_result = $form_handler->validate();
        $fixed_values = $validate_result['fixed_values'];
        if($validate_result['success']){
            $row_id = $this->create_item($fixed_values);
            $this->create_success_message();
            
        }
        else{
          SystemMessages::add_err_message("שגיאה בעריכת הרכיב");
          if(!empty($validate_result['err_messages'])){
              foreach($validate_result['err_messages'] as $message){
                SystemMessages::add_err_message($message);
              }
          }
        }
        $this->redirect_to(current_url()); 
    }

    protected function after_add_redirect($new_row_id){
        return $this->redirect_back_to_item(array('id'=>$new_row_id));
    }

    protected function get_item_parents_tree($parent_id,$select_params){
        //to be overriden
        return array();
    }

    protected function after_delete_redirect(){
        return $this->eject_redirect();
    }

    protected function eject_redirect(){
        return $this->redirect_to($this->eject_url());
    }
  
    protected function redirect_back_to_item($item_info){
        return $this->redirect_to($this->url_back_to_item($item_info));
    }

    protected function after_edit_redirect($item_info){
        return $this->redirect_back_to_item($item_info);
    }

    protected function row_error_message(){
        return null;
    }

    public function eject_url(){
        return null;
    }
  
    public function url_back_to_item($item_info){
        return null;
    }

    public function delete_url($item_info){
        return null;
    }

    public function include_edit_view(){
        return null;
    }

    public function include_add_view(){
        return null;
    }

    protected function update_success_message(){
        return null;
  
    }
  
    protected function create_success_message(){
        return null;
  
    }
  
    protected function delete_success_message(){
        return null;
  
    }
  
    protected function get_item_info($row_id){
        return null;
    }
  
  
    protected function get_fields_collection(){
        return null;
    }
  
    protected function update_item($item_id,$update_values){
        return null;
    }

    protected function create_item($create_values){
        return null;
    }

    protected function delete_item($row_id){
        return null;
    }

    protected function get_priority_space($filter_arr, $item_to_id){
        return null;
    }

    public function rearange_priority($filter_arr){
        return null;
    }

    protected function get_base_filter(){
        return array();
    }
}