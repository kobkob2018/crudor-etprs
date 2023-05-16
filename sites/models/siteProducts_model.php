<?php
  class SiteProducts extends TableModel{

    protected static $main_table = 'products';

    public static function search_by_str($search_str){
        $current_site = Sites::get_current_site();  
        $execute_arr = array('site_id'=>$current_site['id']);
        return array();
    }
  }
?>