<?php
  class Migration_pageController extends CrudController{
    public $add_models = array("sites","migration_site", "migration_page");

    public function list(){
      //if(session__isset())
      $filter_arr = $this->get_base_filter();
      $migration_site = Migration_site::find($filter_arr);      
      $this->data['migration_site'] = $migration_site;
      if(!$migration_site){
        SystemMessages::add_err_message("יש לבחור אתר לייבוא");
        return $this->redirect_to(inner_url("migration_site/list/"));
      }
      else{
          
          $migrate_page_list = Migration_page::get_old_site_page_list($migration_site);
          $this->data['migrate_page_list'] = $migrate_page_list;
      }
      return $this->include_view("migration_page/list.php");
    }

    protected function get_base_filter(){

        $filter_arr = array(
            'site_id'=>$this->data['work_on_site']['id']
        );  
        return $filter_arr;     
    }

	public function import_page(){
		$this->set_layout("blank");
		$filter_arr = $this->get_base_filter();
		$migration_site = Migration_site::find($filter_arr);
		$page_id = $_REQUEST['page_id'];
		
		$import_info = Migration_page::import_page($page_id,$migration_site);
		$return_array = array(
			"old_page_id"=>$page_id,
			"page_id"=>$import_info['page_id'],
			"version"=>$import_info['version'],
			"cat_str"=>$import_info['cat_str']
		);
		print(json_encode($return_array));
		return;
	}
	
	public function delete_migration(){
		$this->set_layout("blank");
		$filter_arr = $this->get_base_filter();
		$migration_site = Migration_site::find($filter_arr);
		$page_id = $_REQUEST['page_id'];
		$delete_info = Migration_page::delete_migrated_page($page_id,$migration_site);
		$return_array = array(
			"old_page_id"=>$delete_info['old_page_id'],
			"page_id"=>$page_id
		);
		print(json_encode($return_array));
		return;
	}

  }
?>