<?php
  class Page_style extends TableModel{

    protected static $main_table = 'page_style';

    public static $fields_collection = array(

        'page_layout'=>array(
            'label'=>'מבנה הדף',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'דף תוכן'),
                array('value'=>'1', 'title'=>'דף נחיתה'),
                array('value'=>'2', 'title'=>'דף משולב'),
                array('value'=>'3', 'title'=>'דף פורטל משולב')
            )
        ),

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
            'label'=>'תגיות עיצוב חופשי וסקריפטים לתגית HEAD',
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