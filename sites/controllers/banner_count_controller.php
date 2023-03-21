<?php
  class Banner_countController extends CrudController{
    public $add_models = array("siteNet_banners");

    protected function add_banner_count($column_name){
        $this->set_layout('blank');
        if(!isset($_REQUEST ['banner_id'])){
            return;
        }
        
        $banner_id = $_REQUEST['banner_id'];

        $session_banner_counts = array();
        if(session__isset('banner_counts')){
            $session_banner_counts = session__get('banner_counts');
        }
        if(!isset($session_banner_counts[$column_name])){
            $session_banner_counts[$column_name] = array();
        }
        if(isset($session_banner_counts[$column_name][$banner_id])){
            return;
        }
        $session_banner_counts[$column_name][$banner_id] = '1';
        session__set('banner_counts',$session_banner_counts);
        
        SiteNet_banners::add_count_to_banner($banner_id,$column_name);
    }

    public function views(){
        
        return $this->add_banner_count('views');
    }

    public function clicks(){
        return $this->add_banner_count('clicks');
    }
  }
?>