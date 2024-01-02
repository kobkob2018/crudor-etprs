<?php
  class AdminSites extends TableModel{

    protected static $main_table = 'sites';

    protected static $auto_delete_from_attached_tables = array(
        'site_colors'=>array(
            'table'=>'site_colors',
            'id_key'=>'site_id'
        ),
        'site_css'=>array(
            'table'=>'site_css',
            'id_key'=>'site_id'
        ),
        'site_styling'=>array(
            'table'=>'site_styling',
            'id_key'=>'site_id'
        ),
        'biz_forms'=>array(
            'table'=>'biz_forms',
            'id_key'=>'site_id'
        ),
        'content_blocks'=>array(
            'table'=>'content_blocks',
            'id_key'=>'site_id'
        ),
        'content_pages'=>array(
            'table'=>'content_pages',
            'id_key'=>'site_id'
        ),
        'gallery'=>array(
            'table'=>'gallery',
            'id_key'=>'site_id'
        ),
        'gallery_cat'=>array(
            'table'=>'gallery_cat',
            'id_key'=>'site_id'
        ),
        'menu_items'=>array(
            'table'=>'menu_items',
            'id_key'=>'site_id'
        ),
        'news'=>array(
            'table'=>'news',
            'id_key'=>'site_id'
        ),
        'products'=>array(
            'table'=>'products',
            'id_key'=>'site_id'
        ),
        'product_cat'=>array(
            'table'=>'product_cat',
            'id_key'=>'site_id'
        ),
        'product_sub'=>array(
            'table'=>'product_sub',
            'id_key'=>'site_id'
        ),
        'user_sites'=>array(
            'table'=>'user_sites',
            'id_key'=>'site_id'
        ),
        'site_user_can'=>array(
            'table'=>'site_user_can',
            'id_key'=>'site_id'
        ),
    );

    public static $fields_collection = array(

        'title'=>array(
            'label'=>'שם האתר',
            'type'=>'text',
            'validation'=>'required'
        ),

        'is_secure'=>array(
            'label'=>'יש HTTPS',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'domain'=>array(
            'label'=>'דומיין',
            'type'=>'text',
            'validation'=>'required'
        ),

        'iso_code'=>array(
            'label'=>'שפה',
            'type'=>'select',
            'default'=>'he_IL',
            'options_method'=>array('model'=>'adminSites','method'=>'get_select_language_options')
        ),

        'disable_robots'=>array(
            'label'=>'הסתר ממנועי החיפוש של גוגל',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'meta_title'=>array(
            'label'=>'כותרת מטא',
            'type'=>'text'
        ), 
              
        'logo'=>array(
            'label'=>'לוגו',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'100000',
            'upload_to'=>'site',
            'name_file'=>'logo.{{ext}}'
        ), 

        'favicon'=>array(
            'label'=>'פביקון',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'10000',
            'upload_to'=>'site',
            'name_file'=>'favicon.{{ext}}'
        ), 

        'home_page'=>array(
            'label'=>'דף הבית',
            'type'=>'select',
            'select_blank'=>array('value'=>'0','label'=>'---'),
            'options_method'=>array('model'=>'adminPages','method'=>'get_select_options')
        ),

        'meta_description'=>array(
            'label'=>'תיאור מטא',
            'type'=>'textbox',
            'css_class'=>'small-text left-text'
        ),  

        'meta_keywords'=>array(
            'label'=>'מילות מפתח',
            'type'=>'textbox',
            'css_class'=>'small-text left-text'
        ),

        'use_recapcha'=>array(
            'label'=>'הוספת קאבצה בטפסים(בתנאי שיש מפתחות לאתר)',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),
        'recapcha_public'=>array(
            'label'=>'מפתח קאבצה ציבורי',
            'type'=>'text'
        ), 
        'recapcha_secret'=>array(
            'label'=>'מפתח קאבצה סודי',
            'type'=>'text'
        ), 

    );

    public static function get_select_language_options(){
        $filter_arr = array('system_id'=>'admin');
        $languages = self::simple_get_list_by_table_name($filter_arr,'languages');
        $return_options = array();
        $default_found = false;
        foreach($languages as $language){
            if($language['iso_code'] == 'he_IL'){
                $default_found = true;
            }
            $return_options[] = array('value'=>$language['iso_code'],'title'=>$language['label'].'('.$language['iso_code'].')');
        }
        if(!$default_found){
            $return_options[] = array('value'=>'he_IL','title'=>'PLEASE CREATE he_IL language as default language!!');
        }
        return $return_options;
    }
}
?>