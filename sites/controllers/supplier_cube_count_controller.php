<?php
  class Supplier_cube_countController extends CrudController{
    public $add_models = array("siteSupplier_cubes");

    protected function add_count($column_name){
        $this->set_layout('blank');
        if(!isset($_REQUEST ['cube_id'])){
            return;
        }
        
        $cube_id = $_REQUEST['cube_id'];

        $session_cube_counts = array();
        if(session__isset('cube_counts')){
            $session_cube_counts = session__get('cube_counts');
        }
        if(!isset($session_cube_counts[$column_name])){
            $session_cube_counts[$column_name] = array();
        }
        if(isset($session_cube_counts[$column_name][$cube_id])){
            return;
        }
        $session_cube_counts[$column_name][$cube_id] = '1';
        session__set('cube_counts',$session_cube_counts);
        
        SiteSupplier_cubes::add_count_to_cube($cube_id,$column_name);
    }

    public function views(){
        return $this->add_count('views');
    }

    public function clicks(){
        return $this->add_count('clicks');
    }
  }
?>