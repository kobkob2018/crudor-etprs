<?php
  class Migration_siteController extends CrudController{
    public $add_models = array("sites","migration_site");

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $migration_site = Migration_site::get_list($filter_arr,"id");      
        $this->data['migration_site'] = $migration_site;
        if(empty($migration_site)){
            return $this->redirect_to(inner_url("migration_site/add/"));
        }
        else{
            $migration_site_id = $migration_site[0]['id'];
            return $this->redirect_to(inner_url("migration_site/edit/?row_id=$migration_site_id"));
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
        $this->include_view('migration_site/edit.php');
    }

    public function include_add_view(){
        $this->include_view('migration_site/add.php');
    }   

    protected function update_success_message(){
        SystemMessages::add_success_message("התיאום עודכן בהצלחה");

    }

    protected function create_success_message(){
        SystemMessages::add_success_message("התיאום נוצר בהצלחה");

    }

    protected function delete_success_message(){
        SystemMessages::add_success_message("התיאום נמחק");
    }

    protected function row_error_message(){
      SystemMessages::add_err_message("לא נבחר תיאום");
    }   

    protected function delete_item($row_id){
      return Migration_site::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Migration_site::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('migration_site/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("migration_site/list/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return Migration_site::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){


        $old_domain = $update_values['old_domain'];
        $old_site_data = Migration_site::get_old_site_data_by_domain($old_domain);
        if(!$old_site_data){
            SystemMessages::add_err_message("לא נמצא אתר עם הדומיין שציינת במערכת הישנה");
            $this->eject_redirect();
            return false;
        }
        $update_values['old_unk'] = $old_site_data['unk'];
        $update_values['old_id'] = $old_site_data['site_id'];
        $update_values['old_title'] = $old_site_data['title'];
        $update_values['old_has_ssl'] = $old_site_data['has_ssl'];
        return Migration_site::update($item_id,$update_values);
    }

    protected function get_priority_space($filter_arr, $item_to_id){
        return Migration_site::get_priority_space($filter_arr, $item_to_id);
      }

    protected function create_item($fixed_values){
        $work_on_site = Sites::get_user_workon_site();
        $site_id = $work_on_site['id'];

        $fixed_values['site_id'] = $site_id;
        $old_domain = $fixed_values['old_domain'];
        $old_site_data = Migration_site::get_old_site_data_by_domain($old_domain);
        if(!$old_site_data){
            SystemMessages::add_err_message("לא נמצא אתר עם הדומיין שציינת במערכת הישנה");
            $this->eject_redirect();
            return false;
        }
        $fixed_values['old_unk'] = $old_site_data['unk'];
        $fixed_values['old_id'] = $old_site_data['site_id'];
        $fixed_values['old_title'] = $old_site_data['title'];
        $fixed_values['old_has_ssl'] = $old_site_data['has_ssl'];
        return Migration_site::create($fixed_values);
    }

  }
?>