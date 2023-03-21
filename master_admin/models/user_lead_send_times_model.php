<?php
  class User_lead_send_times extends TableModel{

    protected static $main_table = 'user_lead_send_times';

    public static $fields_collection = array(

        'display'=>array(
            'label'=>'הצג לפי שעות',
            'type'=>'select',
            'default'=>'0',
            'select_blank'=>false,
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'css_class'=>'display-toggle'
        ),
        'time_groups'=>array(
            'label'=>'שעות',
            'type'=>'build_method',
            'build_method'=>'build_time_groups',
            'default'=>'0'
        ), 

    );
}
?>