<?php
  class Biz_formsController extends CrudController{
    public $add_models = array("sites","adminPages", "biz_forms", "biz_categories");

    protected function init_setup($action){
        $page_id = $this->add_page_info_data();
        if(!$page_id){
            return $this->redirect_to(inner_url("pages/list/"));
            return false;
        }
        return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $payload = array();
        $biz_forms = Biz_forms::get_list($filter_arr,"id", $payload);      
        $this->data['biz_forms'] = $biz_forms;
        $page_id = $_GET['page_id'];
        if(empty($biz_forms)){
            return $this->redirect_to(inner_url("biz_forms/add/?page_id=$page_id"));
        }
        else{
            $biz_form_id = $biz_forms[0]['id'];
            return $this->redirect_to(inner_url("biz_forms/edit/?page_id=$page_id&row_id=$biz_form_id"));
        }
        //$this->include_view('biz_forms/list.php');

    }

    protected function add_page_info_data(){

        if(!isset($_GET['page_id'])){
            return false;
        }
        $page_id = $_GET['page_id'];
        $page_info = AdminPages::get_by_id($page_id, 'id, title, link');
        $this->data['page_info'] = $page_info;
        if($page_info && isset($page_info['id'])){
            return $page_info['id'];
        }
    }

    protected function get_base_filter(){
        $page_id = $this->add_page_info_data();
        if(!$page_id){
            return;
        }

        $filter_arr = array(
            'site_id'=>$this->data['work_on_site']['id'],
            'page_id'=>$page_id,
    
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

    public function set_priority(){
        return parent::set_priority();
    }

    public function rearange_priority($filter_arr){
        $work_on_site = Sites::get_user_workon_site();
        $site_id = $work_on_site['id'];
        $filter_arr = array();
        $filter_arr['site_id'] = $site_id;
        $filter_arr['page_id'] = $this->data['page_info']['id'];
        return Biz_forms::rearange_priority($filter_arr);
    }

    public function build_biz_cat_selector($field_key, $build_field){
        
        $selected_cat_id = $this->get_form_input($field_key);
        
        $cat_select_options = false;
        if($selected_cat_id != '0'){
            $cat_tree = Biz_categories::get_item_parents_tree($selected_cat_id,'id, label, parent');
            //if the selected category was deleted... 
            if(empty($cat_tree)){
                $selected_cat_id = '0';
                $cat_tree = Biz_categories::get_item_parents_tree($selected_cat_id,'id, label, parent');
            }
        }
        else{
            $cat_tree = false;
        }
        $selected_box_parent = '-1';
        $active_parent = $selected_cat_id;
        $cat_select_options = Biz_categories::get_children_list_of($selected_cat_id,'id, label');
        
        if(!$cat_select_options && $cat_tree && !empty($cat_tree)){
            //array_pop($cat_tree);
            $selected_cat = $cat_tree[count($cat_tree)-1];
            $selected_box_parent = $selected_cat['id'];
            $active_parent = $selected_cat['parent'];
            $cat_select_options = Biz_categories::get_children_list_of($selected_cat['parent'],'id, label');
            
        }
        foreach($cat_select_options as $key=>$cat){
            $selected_str = '';
            if($selected_cat_id == $cat['id']){
                $selected_str = 'selected';
            }
            $cat_select_options[$key]['selected'] = $selected_str;
        }
        $this->include_view("biz_categories/category_selector.php", 
            array('field_key'=>$field_key, 
                'field_name'=>"row[$field_key]", 
                'selected_cat_id'=>$selected_cat_id,
                'build_field'=>$build_field,
                'cat_tree'=>$cat_tree,
                'select_options'=>$cat_select_options,
                'selected_box_parent'=>$selected_box_parent,
                'active_parent'=>$active_parent)
        );
    } 

    public function include_edit_view(){
        $this->include_view('biz_forms/edit.php');
    }

    public function include_add_view(){
        $this->include_view('biz_forms/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("הטופס עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("הטופס נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("הטופס נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר טופס");
    }   

    protected function delete_item($row_id){
      return Biz_forms::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Biz_forms::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('biz_forms/list/?page_id='.$this->data['page_info']['id']);
    }

    public function url_back_to_item($item_info){
      return inner_url("biz_forms/list/?page_id=".$this->data['page_info']['id']);
    }

    protected function get_fields_collection(){
      return Biz_forms::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Biz_forms::update($item_id,$update_values);
    }

    protected function get_priority_space($filter_arr, $item_to_id){
        return Biz_forms::get_priority_space($filter_arr, $item_to_id);
      }

    protected function create_item($fixed_values){

        $work_on_site = Sites::get_user_workon_site();
        $site_id = $work_on_site['id'];
        $fixed_values['site_id'] = $site_id;
        $fixed_values['page_id'] = $this->data['page_info']['id'];
        return Biz_forms::create($fixed_values);
    }
    
    public function create_biz_cat_selector(){
        echo "This will be the biz_cat selector";
    }

  }
?>