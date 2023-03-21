<?php
  class AdminSites extends TableModel{

    protected static $main_table = 'sites';


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
        ) 

    );
}
?>