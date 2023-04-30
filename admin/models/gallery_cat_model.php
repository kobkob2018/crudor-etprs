<?php
  class gallery_cat extends TableModel{


    protected static $main_table = 'gallery_cat';

    protected static $auto_delete_from_attached_tables = array(
        'gallery_cat_assign'=>array(
            'table'=>'gallery_cat_assign',
            'id_key'=>'cat_id'
        )
    );  

    public static $fields_collection = array(
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
    );

}
?>