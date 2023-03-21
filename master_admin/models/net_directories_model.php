<?php
  class Net_directories extends TableModel{

    protected static $main_table = 'net_directories';


    public static $fields_collection = array(

        'label'=>array(
            'label'=>'שם התיקייה',
            'type'=>'text',
            'validation'=>'required'
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
    );
}
?>