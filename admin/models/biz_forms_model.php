<?php
  class Biz_forms extends TableModel{

    protected static $main_table = 'biz_forms';


    public static $fields_collection = array(

        'title'=>array(
            'label'=>'כותרת',
            'type'=>'text',
            'validation'=>''
        ),

        'cat_id'=>array(
            'label'=>'קטגוריה',
            'type'=>'build_method',
            'build_method'=>'build_biz_cat_selector',
            'default'=>'0'
        ),        

        'active'=>array(
            'label'=>'סטטוס',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא פעיל'),
                array('value'=>'1', 'title'=>'פעיל')
            )
        ),

        'thanks_pixel'=>array(
            'label'=>'פיקסל לאחר שליחה',
            'type'=>'textbox',
            'css_class'=>'small-text left-text'
        ),   

        'thanks_redirect'=>array(
            'label'=>'הפנייה 5 שניות לאחר שליחה',
            'type'=>'text',
            'css_class'=>'left-text'
        ), 

        'input_remove'=>array(
            'label'=>'הסרת שדות',
            'type'=>'text',
        ),

        'add_email'=>array(
            'label'=>'שדה אימייל',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'הסר'),
                array('value'=>'1', 'title'=>'הוסף')
            )
        ),

        'bill_type'=>array(
            'label'=>'סוג חיוב',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'רגיל'),
                array('value'=>'1', 'title'=>'ללא חיוב')
            )
        ),

        'limit_by_cities'=>array(
            'label'=>'סינון לפי עיר',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            )
        ),

    );
}
?>