<?php
  class Migration_catController extends CrudController{
    public $add_models = array("sites","migration_cat");

    public function list(){
        //if(session__isset())

        $migration_cat_list = Migration_cat::get_old_cat_tree();
        $current_cat_list = Migration_cat::get_new_cat_tree();
        
        $this->data['migrate_cat_list'] = $migration_cat_list;
        $this->data['current_cat_list'] = $current_cat_list;
        return $this->include_view("migration_cat/list.php");
        
    }

    public function fetch_sub_cats_current(){
        $cat_id = $_GET['cat_id'];
        $current_cat_list = Migration_cat::get_new_cat_tree($cat_id);
        $this->data['current_sub_cat_list'] = $current_cat_list;
        $this->set_layout('blank');
        return $this->include_view("migration_cat/sub_list_current.php");
        
    }

    public function pair_remove(){
        $this->set_layout("blank");
        $old_cat_id = $_REQUEST['old_cat_id'];
        $return_array = array(
            "success"=>true,
            "old_cat_id"=>$old_cat_id,
            "old_cat_remove"=>"-1"
        );
        $old_cat_pair = Migration_cat::get_old_cat_pair($old_cat_id);
        
        if($old_cat_pair){
            $return_array['old_cat_remove'] = $old_cat_pair['old_cat_id'];
            Migration_cat::remove_old_cat_pair($old_cat_id);
        }
        print(json_encode($return_array));
        exit();
    }

    public function pair_go(){
        $this->set_layout("blank");
        $cat_id = $_REQUEST['cat_id'];
        $pair_cat = $_REQUEST['pair_cat'];
        $return_array = array(
            "success"=>true,
            "cat_id"=>$cat_id,
            "cat_label"=>"",
            "old_cat_label"=>""
        );

        Migration_cat::remove_old_cat_pair($pair_cat);
        
        Migration_cat::add_cat_pair($cat_id, $pair_cat);                 
        $return_array['cat_label'] = Migration_cat::get_cat_label($cat_id);
        $old_cat_label = Migration_cat::get_old_cat_label($pair_cat);
        
        $return_array['old_cat_label'] = $old_cat_label;
        
        print(json_encode($return_array));
        exit();
    }

  }
?>