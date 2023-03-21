<?php
  class MenusController extends Controller{
    public $add_models = array("adminMenuItems");
    protected $list_forms = array();

    protected function init_setup($action){
      $this->data['site'] = Sites::get_user_workon_site();
      return parent::init_setup($action);
    }

    public function right_menu(){
        $this->data['action_name'] = 'right_menu';
        $this->data['page_title'] = "תפריט ימני";
        $this->data['menu_identifier'] = 'right';
        return $this->manage_menu();
    }

    public function top_menu(){
        $this->data['action_name'] = 'top_menu';
        $this->data['page_title'] = "תפריט עליון";
        $this->data['menu_identifier'] = 'top';
        return $this->manage_menu();
    }

    public function bottom_menu(){
        $this->data['action_name'] = 'bottom_menu';
        $this->data['page_title'] = "תפריט תחתון";
        $this->data['menu_identifier'] = 'bottom';
        return $this->manage_menu();
    }

    public function hero_menu(){
        $this->data['action_name'] = 'hero_menu';
        $this->data['page_title'] = "תפריט הירו";
        $this->data['menu_identifier'] = 'hero';
        return $this->manage_menu();
    }

    protected function manage_menu(){
        $action_url = 'menus/'.$this->data['action_name'].'/';
        $item_url = '';
        if(isset($_GET['item_id'])){
            $item_url = "?item_id=".$_GET['item_id'];
        }
        $this->data['action_url'] = inner_url($action_url);
        $this->data['return_url'] = inner_url($action_url.$item_url);
        if(isset($_GET['delete'])){
            return $this->delete_item($_REQUEST['delete']);   
        }


        $this->data['menu_id'] = AdminMenuItems::$menu_type_list[$this->data['menu_identifier']];
        
        if(isset($_REQUEST['move_item'])){
            return $this->move_item_prepare($_REQUEST['move_item']);
        }       
        
        if(session__isset('menu_item_to_move')){
            $move_item_id = session__get('menu_item_to_move');
            $move_menu_item_tree = AdminMenuItems::get_item_parents_tree($move_item_id,'id, title');
            $this->data['move_menu_item'] = array(
                'item_id'=>$move_item_id,
                'tree'=>$move_menu_item_tree
            );
        }

        $item_id = '0';
        if(isset($_GET['item_id'])){
            $item_id = $_GET['item_id'];
            $this->data['item_id'] = $item_id;

            //setup form for the current item
            $main_menu_item = AdminMenuItems::get_by_id($item_id);
            $menu_item_list = AdminMenuItems::get_children_list_of($item_id);
            $item_identifier = "item_".$item_id;
            $main_menu_item = $this->setup_item_form_handler($item_identifier,$main_menu_item);
        }
        else{
            $main_menu_item = false;
            $menu_item_list = AdminMenuItems::get_parent_list_of($this->data['site']['id'],$this->data['menu_id']);
        }

        $item_parent_tree = AdminMenuItems::get_item_parents_tree($item_id,'id, title');
        $this->data['item_parent_tree'] = $item_parent_tree;

        foreach($menu_item_list as $item_key=>$item){
            $item_identifier = "item_".$item['id'];
            //setup form for all children items
            $item = $this->setup_item_form_handler($item_identifier,$item);
            $menu_item_list[$item_key] = $item;
        }


        $this->data['main_menu_item'] = $main_menu_item;
        $this->data['menu_item_list'] = $menu_item_list;
        $this->data['item_id'] = $item_id;


        //setup form for the new item (with the 'main' identifier)
        $form_handler = $this->init_form_handler();
        $form_handler->update_fields_collection(AdminMenuItems::setup_field_collection());

        $this->send_action_proceed();

        $this->include_view('menus/list_items.php');
    }

    public function updateSend(){

        if(!isset($_REQUEST['row_id'])){
            return;
        }
        $row_id = $_REQUEST['row_id'];
        $item_identifier = "item_".$row_id;
        
        if(!isset($this->form_handlers[$item_identifier])){
            return;
        }
        $form_handler = $this->form_handlers[$item_identifier];

        $validate_result = $form_handler->validate();
        $fixed_values = $validate_result['fixed_values'];
        if($validate_result['success']){
            AdminMenuItems::update($row_id,$fixed_values);
            SystemMessages::add_success_message("הקישור נשמר בהצלחה");
            $this->redirect_to(current_url());
        }
        else{
            if(!empty($validate_result['err_messages'])){
                $this->data['form_err_messages'] = $validate_result['err_messages'];
            }
        }
    }


    public function createSend(){
        
        $form_handler = $this->init_form_handler();

        $validate_result = $form_handler->validate();
        $fixed_values = $validate_result['fixed_values'];
        if($validate_result['success']){
            AdminMenuItems::create($fixed_values);
            SystemMessages::add_success_message("הקישור נשמר בהצלחה");
            $this->redirect_to(current_url());
        }
        else{
            if(!empty($validate_result['err_messages'])){
                $this->data['form_err_messages'] = $validate_result['err_messages'];
            }
        }
    }

    
    public function delete_item($item_id){
        AdminMenuItems::delete_with_offsprings($item_id);
        SystemMessages::add_success_message("הקישור נמחק בהצלחה"); 
        return $this->redirect_to($this->data['return_url']);      
    }  

    protected function move_item_prepare($item_identifier){
        if($item_identifier == 'cancel'){
            session__unset('menu_item_to_move');
        }
        elseif($item_identifier == 'here'){
            $item_to_move_id = session__get('menu_item_to_move');
            $parent_id = 0;
            if(isset($_GET['item_id'])){
                $parent_id = $_GET['item_id'];
            }
            $parent_tree = AdminMenuItems::get_item_parents_tree($parent_id,'id');
            foreach($parent_tree as $branch){
                if($item_to_move_id == $branch['id']){
                    SystemMessages::add_err_message("לא ניתן להעביר קישור לצאצאיו");
                    return $this->redirect_to($this->data['return_url']);
                }
            }
            AdminMenuItems::update($item_to_move_id,array('parent'=>$parent_id));
            SystemMessages::add_success_message("הקישור הועבר בהצלחה");
            session__unset('menu_item_to_move');
        }
        else{
            session__set('menu_item_to_move',$item_identifier);
        }
        return $this->redirect_to($this->data['return_url']);
    }


    private function setup_item_form_handler($item_key,$item){
        if(!$item || $item == null){
            return $item;
        }
        
        //setup form for specific item (like the parent item or the children items) children items
        $form_handler = $this->init_form_handler($item_key);
        $form_handler->update_fields_collection(AdminMenuItems::setup_field_collection());
        $form_handler->setup_db_values($item);
        $item['form_identifier'] = $item_key;
        return $item;
    }
  }
?>