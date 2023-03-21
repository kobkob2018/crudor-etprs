<?php
  class SitePage_style extends TableModel{

    protected static $main_table = 'page_style';

    protected static $page_style_set = false;
    protected static $page_style = false;
    public static function get_current_page_style(){
        if(self::$page_style_set){
            return self::$page_style;
        }
        $current_page = SitePages::get_current_page();
        $site = Sites::get_current_site();
        if(!$current_page){
            return false;
        }
        $filter_arr = array(
            'page_id'=>$current_page['id'],
            'site_id'=>$site['id']
        );
        $page_style = false;
        $current_page_style_arr = self::get_list($filter_arr);
        if(isset($current_page_style_arr[0])){
            $page_style = $current_page_style_arr[0];
        }
        self::$page_style_set = true;
        self::$page_style = $page_style;
        return self::$page_style;
    }
}
?>