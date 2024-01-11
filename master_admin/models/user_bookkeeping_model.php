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

        'hostEndDate'=>array(
            'label'=>'תאריך תפוגת אחסון',
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

        'domainEndDate'=>array(
            'label'=>'תאריך תפוגת דומיין',
            'type'=>'date',
            'default'=>'2050-01-01',
            'validation'=>'required, date'
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

    public static function get_users_to_alert_hosting($days_before){ //DATE_SUB(NOW() + INTERVAL 1 DAY)
//SELECT * FROM user_bookkeeping WHERE hostPriceMon > 0 AND hostEndDate = DATE_SUB(NOW(), INTERVAL 1 DAY);
        //SELECT * FROM user_bookkeeping WHERE hostPriceMon > 0 AND hostEndDate = DATE_SUB(NOW() + INTERVAL 1 DAY)
        $db = Db::getInstance();
        $sql = "SELECT * FROM user_bookkeeping WHERE hostPriceMon > 0 AND hostEndDate = DATE(NOW() + INTERVAL :days_before DAY)";
        $req = $db->prepare($sql);
        $sql_arr = array('days_before'=>$days_before);
        $req->execute($sql_arr);
        $book_list = $req->fetchAll();
        foreach($book_list as $book_id=>$book){
            $book['user'] = Users::get_by_id($book['user_id']);
            $book['hostPriceYear'] = round($book['hostPriceMon']*12*1.17);
            $book_list[$book_id] = $book;
        }
        return $book_list;
    }

    public static function get_users_to_alert_domain($days_before){
        $db = Db::getInstance();
        $sql = "SELECT * FROM user_bookkeeping WHERE domainPrice > 0 AND domainEndDate = DATE(NOW() + INTERVAL :days_before DAY)";
        $req = $db->prepare($sql);
        $sql_arr = array('days_before'=>$days_before);
        $req->execute($sql_arr);
        $book_list = $req->fetchAll();
        foreach($book_list as $book_id=>$book){
            $book['user'] = Users::get_by_id($book['user_id']);
            $book['domainPriceTotal'] = round($book['domainPrice']*1.17);
            $book_list[$book_id] = $book;
        }
        return $book_list;
    }

    public static function get_users_to_alert_domain_admin($days_before){
        $db = Db::getInstance();
        $sql = "SELECT * FROM user_bookkeeping WHERE domainPrice = 0 AND domainEndDate = DATE(NOW() + INTERVAL :days_before DAY)";
        $req = $db->prepare($sql);
        $sql_arr = array('days_before'=>$days_before);
        $req->execute($sql_arr);
        $book_list = $req->fetchAll();
        foreach($book_list as $book_id=>$book){
            $book['user'] = Users::get_by_id($book['user_id']);
            $book['domainPriceTotal'] = round($book['domainPrice']*1.17);
            $book_list[$book_id] = $book;
        }
        return $book_list;
    }

}
?>