<?php
  class Site_stylingController extends CrudController{
    public $add_models = array("sites","site_styling");

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $site_styling = Site_styling::get_list($filter_arr,"id");      
        $this->data['site_styling'] = $site_styling;
        if(empty($site_styling)){
            return $this->redirect_to(inner_url("site_styling/add/"));
        }
        else{
            $site_styling_id = $site_styling[0]['id'];
            return $this->redirect_to(inner_url("site_styling/edit/?row_id=$site_styling_id"));
        }
    }

    protected function get_base_filter(){

        $filter_arr = array(
            'site_id'=>$this->data['work_on_site']['id']
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
        $this->include_view('site_styling/edit.php');
    }

    public function include_add_view(){
        $this->include_view('site_styling/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("העיצוב עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("העיצוב נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("העיצוב נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר עיצוב");
    }   

    protected function delete_item($row_id){
      return Site_styling::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Site_styling::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('site_styling/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("site_styling/list/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return Site_styling::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Site_styling::update($item_id,$update_values);
    }

    protected function get_priority_space($filter_arr, $item_to_id){
        return Site_styling::get_priority_space($filter_arr, $item_to_id);
      }

    protected function create_item($fixed_values){

        $work_on_site = Sites::get_user_workon_site();
        $site_id = $work_on_site['id'];
        $fixed_values['site_id'] = $site_id;
        return Site_styling::create($fixed_values);
    }

  }
?>