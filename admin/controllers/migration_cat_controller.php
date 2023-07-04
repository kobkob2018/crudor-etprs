<?php
  class Migration_catController extends CrudController{
    public $add_models = array("sites","migration_cat");

    public function list(){
        //if(session__isset())

        $migration_cat_list = Migration_cat::get_old_cat_tree();
        return $this->include_view("migration_cat/list.php");
        return;
        $migration_site_id = $migration_site['id'];
        $migrate_page_list = Migration_page::get_old_site_page_list($migration_site);
        foreach($migrate_page_list as $migrate_page){
            print_help($migrate_page['name']);
        }
        
    }

  }
?>