<?php
  class User_phones extends TableModel{

    protected static $main_table = 'user_phones';

    public static $fields_collection = array(

        'number'=>array(
            'label'=>'מספר',
            'type'=>'text',
            'validation'=>'required'
        ),

        'campaign_type'=>array(
            'label'=>'סוג קמפיין',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'ללא'),
                array('value'=>'2', 'title'=>'גוגל'),
                array('value'=>'1', 'title'=>'פייסבוק')
            )
        ),

        'campaign_name'=>array(
            'label'=>'שם קמפיין',
            'type'=>'text'
        ),


        'lead_bill'=>array(
            'label'=>'חיוב שיחה בליד',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            )
        ),

        'misscall_sms_return'=>array(
            'label'=>'החזר SMS בלא מענה',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            )
        ),

        'misscall_sms'=>array(
            'label'=>'SMS בלא מענה',
            'type'=>'textbox',
            'default'=>'מייד נתפנה ונחזור אלייך',
            'css_class'=>'tiny-text',
            'validation'=>'required',
        ),

        'aftercall_sms_send'=>array(
            'label'=>'החזר SMS לאחר שיחה',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            )
        ),

        'aftercall_sms'=>array(
            'label'=>'SMS לאחר שיחה',
            'type'=>'textbox',
            'default'=>'מייד נתפנה ונחזור אלייך',
            'css_class'=>'tiny-text',
            'validation'=>'required',
        ),

        'alert_sms_to'=>array(
            'label'=>'שליחת התראה על שיחה למספר',
            'type'=>'text',
        ),

    );
}
?>