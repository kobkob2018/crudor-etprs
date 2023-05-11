<?php
  class News extends TableModel{

    protected static $main_table = 'news';


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

        'content'=>array(
            'label'=>'תיאור קצר',
            'type'=>'textbox',
            'css_class'=>'small-text'
        ),

        'link'=>array(
            'label'=>'לינק',
            'type'=>'text'
        )
    );
}
?>