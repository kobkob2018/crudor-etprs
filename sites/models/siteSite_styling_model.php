<?php
  class SiteSite_styling extends TableModel{

    protected static $main_table = 'site_styling';
    protected static $current_site_styling = false;
    protected static $current_site_styling_set = false;
    public static function get_current_site_styling(){
        if(self::$current_site_styling_set){
            return self::$current_site_styling;
        }

        $current_site = Sites::get_current_site();
        if(!$current_site){
            return false;
        }
        $filter_arr = array('site_id'=>$current_site['id']);

        $result_arr = self::get_list($filter_arr);

        $result = false;
        if(isset($result_arr[0])){
            $result = $result_arr[0];
        }
        self::$current_site_styling_set = true;
        self::$current_site_styling = $result;
        return self::$current_site_styling;
    }

  }
?>