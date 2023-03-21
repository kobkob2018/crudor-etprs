<?php
  class Quotes extends TableModel{

    protected static $main_table = 'quotes';


    public static $fields_collection = array(

        'priority'=>array(
            'label'=>'מיקום',
            'type'=>'text',
            'default'=>'10',
            'validation'=>'required, int'
        ),

        'label'=>array(
            'label'=>'כותרת',
            'type'=>'text',
            'validation'=>'required'
        ),

        'cat_id'=>array(
            'label'=>'תיקיית אחסון',
            'type'=>'select',
            'options_method'=>array('model'=>'quote_cat','method'=>'get_select_cat_options'),
            'validation'=>'required'
        ),        

        'description'=>array(
            'label'=>'תיאור קצר',
            'type'=>'textbox',
            'default'=>'1',
            'css_class'=>'small-text'
        ),

        'price_text'=>array(
            'label'=>'טקסט למחיר',
            'type'=>'textbox',
            'default'=>'1',
            'css_class'=>'small-text'
        ),

        'price'=>array(
            'label'=>'מחיר',
            'type'=>'text',
            'validation'=>'float'
        ),

        'image'=>array(
            'label'=>'תמונה',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'1000000',
            'upload_to'=>'quotes',
            'name_file'=>'quote_{{row_id}}.{{ext}}'
        ),

        'link'=>array(
            'label'=>'כתובת קישור',
            'type'=>'text'
        ),

        'phone'=>array(
            'label'=>'טלפון',
            'type'=>'text'
        )
    );
}
?>