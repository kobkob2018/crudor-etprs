<?php
  class User_lead_settings extends TableModel{

    protected static $main_table = 'user_lead_settings';

    public static $fields_collection = array(
        'active'=>array(
            'label'=>'פעיל',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'end_date'=>array(
            'label'=>'תאריך תפוגת פעילות',
            'type'=>'text',
            'validation'=>'date_time'
        ),

        'lead_price_no_tax'=>array(
            'label'=>'מחיר ליד לפני מס',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'float'
        ),  

        'lead_price'=>array(
            'label'=>'מחיר ליד אחרי מס',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'float'
        ),        

        'free_send'=>array(
            'label'=>'שליחה חופשית',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'open_mode'=>array(
            'label'=>'מצב פתוח',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'auto_send'=>array(
            'label'=>'שליחה אוטומטית',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),



        'lead_credit'=>array(
            'label'=>'קרדיט לידים',
            'type'=>'text',
            'default'=>'10',
            'validation'=>'int'
        ),

        'rotation_priority'=>array(
            'label'=>'עדיפות ברוטציה חודשית',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'int'
        ),

        'month_max'=>array(
            'label'=>'מקסימום לידים ברוטציה',
            'type'=>'text',
            'default'=>'10',
            'validation'=>'int'
        ),

        'flex_max'=>array(
            'label'=>'מקסימום גמיש על בסיס מקום פנוי',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),
    );
}
?>