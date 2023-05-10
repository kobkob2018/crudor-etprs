<?php
  class SiteBiz_requests extends TableModel{

    protected static $main_table = 'biz_requests';

    public static $fields_collection = array(

        'cat_id'=>array(
            'label'=>'קטגוריה',
            'type'=>'text',
            'validation'=>'required, int'
        ),

        'full_name'=>array(
            'label'=>'שם מלא',
            'type'=>'text',
            'validation'=>'required, full_name'
        ),

        'phone'=>array(
            'label'=>'טלפון',
            'type'=>'text',
            'validation'=>'required, phone'
        ),

       
    );

    public static function count_weekly_phone_duplications($phone){

        $execute_arr = array("phone"=>$phone);
        $sql = "SELECT COUNT(id) as `times_count` FROM biz_requests WHERE phone = :phone AND date_in > (NOW() - INTERVAL 7 DAY)";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $result = $req->fetch();
        return $result['times_count'];
    }

    public static function get_form_last_requests($form_id){

        $execute_arr = array('form_id'=>$form_id);
        $sql = "SELECT * FROM biz_requests WHERE form_id = :form_id AND date_in > (NOW() - INTERVAL 30 DAY) LIMIT 10";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $result = $req->fetchAll();
        if(!$result){
            return array();
        }
        return $result;
    }

}
?>