<?php
  class Product_sub extends TableModel{

    protected static $main_table = 'product_sub';
    protected static $select_options = false;

    public static $fields_collection = array(
        'label'=>array(
            'label'=>'שם תת התיקייה',
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
    );
}
?>