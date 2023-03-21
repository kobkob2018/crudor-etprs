<?php

  class cat_phone_display_hoursController extends CrudController{
    public $add_models = array("cat_phone_display_hours", "biz_categories");

    protected function init_setup($action){
        $cat_id = $this->add_cat_info_data();
        if(!$cat_id){
            return $this->redirect_to(inner_url("biz_categories/list/"));
            return false;
        }

        $item_parent_tree = Biz_categories::get_item_parents_tree($cat_id,'id, label');
        $this->data['item_parent_tree'] = $item_parent_tree;
        $this->data['current_item_id'] = $cat_id;

        return parent::init_setup($action);
    }

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $hours_info = Cat_phone_display_hours::get_list($filter_arr,"id");      
        $this->data['hours_info'] = $hours_info;
        $cat_id = $_GET['cat_id'];
        if(empty($hours_info)){
            return $this->redirect_to(inner_url("cat_phone_display_hours/add/?cat_id=$cat_id"));
        }
        else{
            $row_id = $hours_info[0]['id'];
            return $this->redirect_to(inner_url("cat_phone_display_hours/edit/?cat_id=$cat_id&row_id=$row_id"));
        }
    }

    protected function add_cat_info_data(){

        if(!isset($_GET['cat_id'])){
            return false;
        }
        $cat_id = $_GET['cat_id'];
        $cat_info = Biz_categories::get_by_id($cat_id, 'id, label');
        $this->data['cat_info'] = $cat_info;
        if($cat_info && isset($cat_info['id'])){
            return $cat_info['id'];
        }
    }

    protected function get_base_filter(){
        $cat_id = $this->add_cat_info_data();
        if(!$cat_id){
            return;
        }

        $filter_arr = array(
            'cat_id'=>$cat_id,
    
        );  
        return $filter_arr;     
    }

    public function edit(){
        return parent::edit();
    }

    public function updateSend(){
        $row_id = $_REQUEST['row_id'];
        $fixed_values = array(
            'display'=>$_REQUEST['row']['display'],
            'time_groups'=>json_encode($_REQUEST['row']['time_groups'])
        );
        
        Cat_phone_display_hours::update($row_id, $fixed_values);
        $this->update_success_message();
        return $this->redirect_to(inner_url('cat_phone_display_hours/edit/?cat_id='.$this->data['cat_info']['id']).'&row_id='.$row_id);
    }

    public function add(){
        return parent::add();
    }       

    public function createSend(){
        $cat_id = $this->add_cat_info_data();
        $fixed_values = array(
            'cat_id'=>$cat_id,
            'display'=>$_REQUEST['row']['display'],
            'time_groups'=>json_encode($_REQUEST['row']['time_groups'])
        );
        $row_id = Cat_phone_display_hours::create($fixed_values);
        $this->update_success_message();
        return $this->redirect_to(inner_url('cat_phone_display_hours/edit/?cat_id='.$this->data['cat_info']['id']).'&row_id='.$row_id);
    }

    public function build_time_groups($field_key, $build_field){
        $hour_groups_json = $this->get_form_input($field_key);
        $hour_groups = json_decode($hour_groups_json, true);
        if(!$hour_groups){
            $hour_groups = array();
        }
        $options = array(
            'hours'=>array(),
            'minutes'=>array(),
            'days'=>array(
                '1'=>array('label'=>'ראשון','value'=>'1','default_checked'=>'checked'),
                '2'=>array('label'=>'שני','value'=>'2','default_checked'=>'checked'),
                '3'=>array('label'=>'שלישי','value'=>'3','default_checked'=>'checked'),
                '4'=>array('label'=>'רביעי','value'=>'4','default_checked'=>'checked'),
                '5'=>array('label'=>'חמישי','value'=>'5','default_checked'=>'checked'),
                '6'=>array('label'=>'שישי','value'=>'6','default_checked'=>''),
                '7'=>array('label'=>'שבת','value'=>'7','default_checked'=>'')
            )
        );
        for($i=0; $i<25; $i++){
            $value = str_pad($i,2,"0",STR_PAD_LEFT);
           // $value = number_format((int)$i, 2);
            $options['hours'][] = array('value'=>$value);
        }
        for($i=0; $i<60; $i++){
            $value = str_pad($i,2,"0",STR_PAD_LEFT);
            $options['minutes'][] = array('value'=>$value);
        }  

        $info_payload = array(
            'fields'=>array(
                $field_key=>array('hour_groups'=>$hour_groups)
            ),
            'options'=>$options
        );
        $this->include_view('form_builder/hours_select.php',$info_payload);
    } 

    public function include_edit_view(){
        $this->include_view('cat_phone_display_hours/edit.php');
    }

    public function include_add_view(){
        $this->include_view('cat_phone_display_hours/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("הזמנים עודכנו בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("הזמנים עודכנו בהצלחה");

    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחרה קטגוריה");
    }   

    protected function get_item_info($row_id){
      return Cat_phone_display_hours::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('cat_phone_display_hours/list/?cat_id='.$this->data['cat_info']['id']);
    }

    protected function get_fields_collection(){
      return Cat_phone_display_hours::setup_field_collection();
    }
    
  }
?>