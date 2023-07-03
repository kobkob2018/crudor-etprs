<?php
  class Page_migrationController extends CrudController{
    public $add_models = array("sites","site_migration", "page_migration");

    public function list(){
        //if(session__isset())
        $filter_arr = $this->get_base_filter();
        $site_migration = Site_migration::find($filter_arr);      
        $this->data['site_migration'] = $site_migration;
        if(!$site_migration){
          SystemMessages::add_err_message("יש לבחור אתר לייבוא");
          return $this->redirect_to(inner_url("site_migration/list/"));
        }
        else{
            $site_migration_id = $site_migration['id'];
            $migrate_page_list = Page_migration::get_old_site_page_list($site_migration);
            $this->data['migrate_page_list'] = $migrate_page_list;
        }
        return $this->include_view("page_migration/list.php");
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
        $this->include_view('site_migration/edit.php');
    }

    public function include_add_view(){
        $this->include_view('site_migration/add.php');
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
      return Site_migration::delete($row_id);
    }

    protected function get_item_info($row_id){
      return Site_migration::get_by_id($row_id);
    }

    public function eject_url(){
      return inner_url('site_migration/list/');
    }

    public function url_back_to_item($item_info){
      return inner_url("site_migration/list/?row_id=".$item_info['id']);
    }

    protected function get_fields_collection(){
      return Site_migration::setup_field_collection();
    }

    protected function update_item($item_id,$update_values){
      return Site_migration::update($item_id,$update_values);
    }

    protected function get_priority_space($filter_arr, $item_to_id){
        return Site_migration::get_priority_space($filter_arr, $item_to_id);
      }

    protected function create_item($fixed_values){
        $work_on_site = Sites::get_user_workon_site();
        $site_id = $work_on_site['id'];

        $fixed_values['site_id'] = $site_id;
        $old_domain = $fixed_values['old_domain'];
        $old_site_data = Site_migration::get_old_site_data_by_domain($old_domain);
        if(!$old_site_data){
            SystemMessages::add_err_message("לא נמצא אתר עם הדומיין שציינת במערכת הישנה");
            $this->eject_redirect();
            return false;
        }
        $fixed_values['old_unk'] = $old_site_data['unk'];
        $fixed_values['old_id'] = $old_site_data['site_id'];
        $fixed_values['old_title'] = $old_site_data['title'];
        
        return Site_migration::create($fixed_values);
    }

  }
?>