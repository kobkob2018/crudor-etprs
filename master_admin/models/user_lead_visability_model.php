<?php
  class User_lead_visability extends TableModel{

    protected static $main_table = 'user_lead_visability';

    public static $fields_collection = array(
        'show_in_sites'=>array(
            'label'=>'הצג באתרים',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'show_in_leads_report'=>array(
            'label'=>'הצג בדוח מספור לידים',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'show_in_misscalls_report'=>array(
            'label'=>'הצג בדוח שיחות שלא נענו',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'send_monthly_report'=>array(
            'label'=>'שלח דוח חודשי אוטומטי',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'send_advanced_report'=>array(
            'label'=>'שלח דוח מתקדם',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'send_lead_alerts'=>array(
            'label'=>'שליחת התראות על עדכון לידים',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'access_refunds'=>array(
            'label'=>'גישה לבקשת זיכויים',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'access_records'=>array(
            'label'=>'גישה להקלטות',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),

        'records_password'=>array(
            'label'=>'סיסמא להקלטות',
            'type'=>'text',
        ),
    );
    public static function create_for_user($user_id){
        $filter_arr = array('user_id'=>$user_id);
        $sql = "SELECT id FROM  user_lead_visability WHERE user_id = :user_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($filter_arr);
        $result_row = $req->fetch();
        if($result_row){
            return $result_row['id'];
        }
        $sql = "INSERT INTO user_lead_visability(user_id) VALUES(:user_id)";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($filter_arr);
        return $db->lastInsertId();
    }

}
?>