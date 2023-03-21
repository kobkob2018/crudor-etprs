<?php
  class SiteBlocks extends TableModel{

    protected static $main_table = 'content_blocks';

    protected static $current_page_blocks = false;

    public static function get_current_page_blocks(){
        if(self::$current_page_blocks){
            return self::$current_page_blocks;
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
        $payload = array("order_by"=>'priority');
        $current_page_blocks = self::get_list($filter_arr,"*",$payload);
        self::$current_page_blocks = $current_page_blocks;
        return self::$current_page_blocks;
    }
}
?>