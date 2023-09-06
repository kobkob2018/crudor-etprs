<?php
  class User_bookkeeping extends TableModel{

    protected static $main_table = 'user_bookkeeping';

    public static $fields_collection = array(

        'hostPriceMon'=>array(
            'label'=>'מחיר אכסון',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'float'
        ),  

        'domainEndDate'=>array(
            'label'=>'תאריך תפוגת דומיין',
            'type'=>'date',
            'default'=>'2050-01-01',
            'validation'=>'required, date'
        ),

        'domainPrice'=>array(
            'label'=>'מחיר דומיין',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'float'
        ), 

        'hostPriceMon'=>array(
            'label'=>'מחיר אכסון',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'float'
        ),  

        'advertisingPrice'=>array(
            'label'=>'מחיר פרסום',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'float'
        ),  

        'advertisingPeriod'=>array(
            'label'=>'מחזור תשלום',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'חיוב ידני'),
                array('value'=>'1', 'title'=>'חודשי'),
                array('value'=>'2', 'title'=>'דו-חודשי'),
                array('value'=>'3', 'title'=>'רבעוני'),
                array('value'=>'6', 'title'=>'חצי שנתי'),
                array('value'=>'12', 'title'=>'שנתי')
            ),
            'validation'=>'required'
        ),

        'sendReport'=>array(
            'label'=>'שלח דוח אוטומטי חדשי',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ), 
        
        'advReport'=>array(
            'label'=>'שלח דוח מתקדם',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ), 

        'advertisingStartDate'=>array(
            'label'=>'תאריך תחילת פרסום',
            'type'=>'date',
            'default'=>'1970-01-01',
            'validation'=>'required, date'
        ),

        'dealClosedPrice'=>array(
            'label'=>'אחוזי תשלום על עסקאות סגורות',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'required, float'
        ),

    );
}
?>