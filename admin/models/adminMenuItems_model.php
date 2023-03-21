<?php
  class AdminMenuItems extends TableModel{


    protected static $main_table = 'menu_items';

    protected static $select_page_options = false;

    public static function get_parent_list_of($site_id,$menu_id){
        $filter_arr = array('site_id'=>$site_id,'menu_id'=>$menu_id,'parent'=>'0');
        return self::simple_get_list($filter_arr);
    }

    public static function get_select_page_options(){
        if(self::$select_page_options){
            return self::$select_page_options;
        }

        $work_on_site = Sites::get_user_workon_site();
        $site_id = $work_on_site['id'];

        $db = Db::getInstance();
        $sql = "SELECT id, title FROM content_pages WHERE site_id = :site_id order by title";
        $req = $db->prepare($sql);
        $req->execute(array('site_id'=>$site_id));
        $page_list = $req->fetchAll();
        $return_options = array();
        foreach($page_list as $page){
            $return_options[] = array('value'=>$page['id'],'title'=>$page['title']);
        }
        self::$select_page_options = $return_options;
        return self::$select_page_options;
    }

    public static $menu_type_list = array(
        'top'=>'1',
        'right'=>'2',
        'hero'=>'3',
        'bottom'=>'4'
    );



    public static $fields_collection = array(
        'parent'=>array(
            'type'=>'hidden'
        ),
        'site_id'=>array(
            'type'=>'hidden'
        ),
        'priority'=>array(
            'label'=>'מיקום',
            'type'=>'text',
            'default'=>'100',
        ),

        'label'=>array(
            'label'=>'תווית',
            'type'=>'text',
            'validation'=>'required'
        ),


        'menu_id'=>array(
            'type'=>'hidden'
        ),   

        'link_type'=>array(
            'label'=>'סוג הקישור',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'כתובת'),
                array('value'=>'1', 'title'=>'קישור לדף'),
                array('value'=>'2', 'title'=>'קבוצה'),
            ),
            'validation'=>'required'
        ),

        'url'=>array(
            'label'=>'כתובת הקישור',
            'type'=>'text',
        ),
        
        'page_id'=>array(
            'label'=>'קישור לדף',
            'type'=>'select',
            'options_method'=>array('model'=>'AdminMenuItems','method'=>'get_select_page_options'),
        ),

        'target'=>array(
            'label'=>'ייפתח ב',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'אותו הדף'),
                array('value'=>'1', 'title'=>'דף חדש')
            ),
            'validation'=>'required'
        ),

        'css_class'=>array(
            'label'=>'תווית עיצוב',
            'type'=>'text',
        ), 

    );
}
?>