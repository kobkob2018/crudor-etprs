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
            'label'=>'תגיות עיצוב חפשי בHEAD',
            'type'=>'textbox',
            'css_class'=>'big-text left-text',
        ),

        'bottom_styling_tags'=>array(
            'label'=>'תגיות עיצוב חפשי בFOOT',
            'type'=>'textbox',
            'css_class'=>'big-text left-text',
        ),

        'html_helper'=>array(
            'label'=>'כלי עזר להעלאת תמונות',
            'type'=>'textbox',
            'reachtext'=>true,
            'css_class'=>'big-text',
        ),

        'add_scrolling_requests'=>array(
            'label'=>'הוסף פניות אחרונות - חדשות רצות',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא פעיל'),
                array('value'=>'1', 'title'=>'פעיל')
            )
        ),

        
    );
}
?>