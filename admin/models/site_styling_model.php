<?php
  class Site_styling extends TableModel{

    protected static $main_table = 'site_styling';


    public static $fields_collection = array(

        'header_html'=>array(
            'label'=>'תוכן ראש',
            'type'=>'textbox',
            'css_class'=>'big-text left-text',
        ),

        'footer_html'=>array(
            'label'=>'תוכן רגל',
            'type'=>'textbox',
            'css_class'=>'big-text left-text',
        ),

        'styling_tags'=>array(
            'label'=>'עיצוב חפשי',
            'type'=>'textbox',
            'css_class'=>'big-text left-text',
        ),

        'html_helper'=>array(
            'label'=>'כלי עזר להעלאת תמונות',
            'type'=>'textbox',
            'reachtext'=>true,
            'css_class'=>'big-text',
        ),
    );
}
?>