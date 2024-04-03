<?php
  class Whatsapp_templates extends TableModel{

    protected static $main_table = 'whatsapp_templates';


    public static $fields_collection = array(

        'label'=>array(
            'label'=>'שם התבנית',
            'type'=>'text',
            'validation'=>'required'
        ),      

        'text'=>array(
            'label'=>'טסקט',
            'type'=>'textbox',
            'css_class'=>'small-text'
        ),

        'header_image'=>array(
            'label'=>'כתובת תמונה',
            'type'=>'text',
            'css_class'=>'big-text'
        ),
        'header_video'=>array(
            'label'=>'כתובת וידאו',
            'type'=>'text',
            'css_class'=>'big-text'
        ),
    );
}
?>