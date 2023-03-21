<?php
  class AdminPages extends TableModel{

    protected static $main_table = 'content_pages';
    protected static $select_opriotns_arr = array();

    public static $fields_collection = array(
        'title'=>array(
            'label'=>'כותרת',
            'type'=>'text',
            'validation'=>'required'
        ),
        'meta_title'=>array(
            'label'=>'כותרת מטא',
            'type'=>'text',
            'validation'=>'required'
        ),
        'link'=>array(
            'label'=>'כתובת URL',
            'type'=>'text',
            'validation'=>'required, url'
        ),       
        'description'=>array(
            'label'=>'תיאור',
            'type'=>'textbox',
        ),

        'content'=>array(
            'label'=>'תוכן ראשי',
            'type'=>'textbox',
        ),

        'right_banner'=>array(
            'label'=>'באנר',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'1000000',
            'upload_to'=>'pages/banners',
            'name_file'=>'r_banner_{{row_id}}.{{ext}}'
        ),      

    );


    public static function get_select_options(){
        if(isset(self::$select_opriotns_arr['pages'])){
            return self::$select_opriotns_arr['pages'];
        }

        $site = Sites::get_user_workon_site();
        $filter_arr = array('site_id'=>$site['id']);
        $page_list = self::get_list($filter_arr);
        $return_options = array();
        foreach($page_list as $page){
            $return_options[] = array('value'=>$page['id'],'title'=>$page['title']);
        }
        self::$select_opriotns_arr['pages'] = $return_options;
        return $return_options;
    }
}
?>