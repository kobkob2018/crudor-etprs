<?php
  class Migration_catController extends CrudController{
    public $add_models = array("sites","migration_cat");

    public function list(){
        //if(session__isset())

        $migration_cat_list = Migration_cat::get_old_cat_tree();
        
        $this->data['migrate_cat_list'] = $migration_cat_list;
        return $this->include_view("migration_cat/list.php");
        
    }

  }
?>