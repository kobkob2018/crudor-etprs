<?php
  class SiteBiz_forms extends TableModel{

    protected static $main_table = 'biz_forms';

    protected static $current_biz_form = false;

    protected static $current_biz_form_set = false;

    public static function get_current_biz_form(){
        if(self::$current_biz_form_set){
            return self::$current_biz_form;
        }
        
        $current_page = SitePages::get_current_page();
        $site = Sites::get_current_site();

        $current_page_is_home_page = false;

        if(!$current_page){
            return false;
        }

        if($current_page['id'] == $site['home_page']){
            $current_page_is_home_page = true;
        }

        $filter_arr = array(
            'page_id'=>$current_page['id'],
            'site_id'=>$site['id']
        );

        $current_biz_form_arr = self::get_list($filter_arr);
        $current_biz_form_found = false;
        if(is_array($current_biz_form_arr) && isset($current_biz_form_arr[0])){
            $current_biz_form_found = true;
            self::$current_biz_form = $current_biz_form_arr[0];
        }
        elseif((!$current_page_is_home_page)){
            $filter_arr = array(
                'page_id'=>$site['home_page'],
                'site_id'=>$site['id']
            );
            $current_biz_form_arr = self::get_list($filter_arr);
            if(is_array($current_biz_form_arr) && isset($current_biz_form_arr[0])){
                $current_biz_form_found = true;
                self::$current_biz_form = $current_biz_form_arr[0];
            }
        }
        if(!$current_biz_form_found){
            self::$current_biz_form = false;
        }
        self::$current_biz_form_set = true;   
        return self::$current_biz_form;
    }
}
?>