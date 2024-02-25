<?php
  class Products extends TableModel{

    protected static $main_table = 'products';


    public static $fields_collection = array(

        'priority'=>array(
            'label'=>'מיקום',
            'type'=>'text',
            'default'=>'10',
            'validation'=>'required, int'
        ),

        'label'=>array(
            'label'=>'שם (לזיהוי בניהול)',
            'type'=>'text',
            'validation'=>'required'
        ),      

        'active'=>array(
            'label'=>'סטטוס פעיל',
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
            'type'=>'text',
            'validation'=>'required',
            'css_class'=>'yellowish'
        ),

        'meta_description'=>array(
            'label'=>'תיאור מטא',
            'type'=>'textbox',
            'css_class'=>'small-text yellowish'
        ),

        'meta_keywords'=>array(
            'label'=>'מילות מפתח',
            'type'=>'text',
            'css_class'=>'yellowish'
        ),

        'title'=>array(
            'label'=>'כותרת',
            'type'=>'text',
            'validation'=>'required'
        ), 
        
        'list_label'=>array(
            'label'=>'כותרת ברשימה',
            'type'=>'text',
            'validation'=>'required'
        ), 

        'description'=>array(
            'label'=>'תיאור קצר',
            'type'=>'textbox',
            'css_class'=>'small-text'
        ),

        'price'=>array(
            'label'=>'מחיר',
            'type'=>'text',
            'validation'=>'float',
            'default'=>'0'
        ),

        'price_special'=>array(
            'label'=>'מחיר מיוחד',
            'type'=>'text',
            'validation'=>'required, float',
            'default'=>'0'
        ),
        
        'image'=>array(
            'label'=>'תמונה קטנה לרשימת מוצרים',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'1000000',
            'upload_to'=>'products',
            'name_file'=>'product_{{row_id}}.{{ext}}'
        ),

        'link'=>array(
            'label'=>'כתובת קישור',
            'type'=>'text'
        ),

        'link_text'=>array(
            'label'=>'טקסט קישור',
            'type'=>'text'
        ),

        'content'=>array(
            'label'=>'תיאור קצר',
            'type'=>'textbox',
            'reachtext'=>true,
            'css_class'=>'small-text'
        ),
    );
}
?>