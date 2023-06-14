<?php
  class Refund_reasons extends TableModel{

    protected static $main_table = 'refund_reasons';

    public static $fields_collection = array(

        'lead_type'=>array(
            'label'=>'לשימוש ב',
            'type'=>'select',
            'default'=>'phone',
            'options'=>array(
                array('value'=>'phone', 'title'=>'לידים טלפוניים'),
                array('value'=>'form', 'title'=>'לידים מטפסים'),
                array('value'=>'all', 'title'=>'כל סוגי הלידים'),
                array('value'=>'admin', 'title'=>'מערכת ניהול בלבד'),
            ),
            'validation'=>'required'
        ),

        'label'=>array(
            'label'=>'תיאור הסיבה',
            'type'=>'text',
            'validation'=>'required'
        ),
    );
}
?>