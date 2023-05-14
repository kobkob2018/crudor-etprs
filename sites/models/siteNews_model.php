<?php
  class SiteNews extends TableModel{

    protected static $main_table = 'news';

    public static function get_site_news($site_id){
        $filter_arr = array('site_id'=>$site_id);
        $payload = array('order_by'=>'priority, label');
        return self::get_list($filter_arr,'*',$payload);
    }

}
?>