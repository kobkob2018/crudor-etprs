<?php
  class User_lounch_fee extends TableModel{

    protected static $main_table = 'user_lounch_fee';

    public static $fields_collection = array(

        'price'=>array(
            'label'=>'מחיר כולל מע"מ',
            'type'=>'text',
            'default'=>'1',
            'validation'=>'required'
        ),
        'details'=>array(
            'label'=>'פירוט התשלום',
            'type'=>'textbox',
            'validation'=>'required',
            'css_class'=>'small-text'
        ),
        'pay_status'=>array(
            'label'=>'סטטוס',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'ממתין לתשלום'),
                array('value'=>'1', 'title'=>'שולם'),
                array('value'=>'5', 'title'=>'בוטל'),
            ),
            'validation'=>'required'
        ),
        'tash'=>array(
            'label'=>'עד תשלומים',
            'type'=>'select',
            'validation'=>'required',
            'default'=>'1',
            'options'=>array(
                array('value'=>'1', 'title'=>'1'),
                array('value'=>'2', 'title'=>'2'),
                array('value'=>'3', 'title'=>'3'),
                array('value'=>'4', 'title'=>'4'),
                array('value'=>'5', 'title'=>'5'),
                array('value'=>'6', 'title'=>'6'),
                array('value'=>'7', 'title'=>'7'),
                array('value'=>'8', 'title'=>'8'),
                array('value'=>'9', 'title'=>'9'),
                array('value'=>'10', 'title'=>'10'),
                array('value'=>'11', 'title'=>'11'),
                array('value'=>'12', 'title'=>'12'),
            ),
        ),
        'email_to_send'=>array(
            'label'=>'שליחת הודעה תשלום לאימייל',
            'type'=>'text',
            'validation'=>'required',
            'default'=>'',
        ),
        'until_date'=>array(
            'label'=>'תאריך אחרון לתשלום',
            'type'=>'date',
            'validation'=>'required',
            'default'=>'',
            'validation'=>'date'
        ),

    );

    public static function default_until_date(){
        $date = date("Y-m-d");
        $newdate = date("Y-m-d", strtotime ( '+1 month' , strtotime ( $date ) )) ;
        return $newdate;
    }

    public static function setup_list_field_collections(){
        $fields_collection = self::$fields_collection;
        return parent::setup_field_collection($fields_collection);
    }

    public static function setup_add_field_collections($user_info){
        $fields_collection = self::$fields_collection;
        unset($fields_collection['pay_status']);
        $fields_collection['email_to_send']['default'] = $user_info['email'];
        $fields_collection['until_date']['default'] = self::default_until_date();
        return parent::setup_field_collection($fields_collection);
    }
}
?>