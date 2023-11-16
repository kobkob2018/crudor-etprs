<?php
  class Quotes_user extends TableModel{

    protected static $main_table = 'quotes_user';


    public static $fields_collection = array(

        'label'=>array(
            'label'=>'שם העסק שיופיע בהצעת המחיר',
            'type'=>'text',
            'validation'=>'required'
        ),       
        'status'=>array(
            'label'=>'הצג הצעות מחיר של הלקוח',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),
        'image'=>array(
            'label'=>'תמונה',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'1000000',
            'upload_to'=>'quotes_user',
            'assets_dir'=>'master',
            'name_file'=>'user_{{row_id}}.{{ext}}'
        ),
        'link'=>array(
            'label'=>'כתובת קישור לדף הלקוח',
            'type'=>'text'
        ),
        'phone'=>array(
            'label'=>'טלפון שיופיע בהצעות המחיר',
            'type'=>'text'
        ),
        'city_name'=>array(
            'label'=>'שם עיר',
            'type'=>'text'
        ),
        
    );

    public static function set_user_quotes_statuses($user_id, $set_to_status){
        $db = DB::getInstance();
        $status_from = '1';
        if($set_to_status == '1'){
            $status_from = '9';
        }
        $sql = "UPDATE quotes SET status = :set_to_status WHERE status = :status_from AND user_id = :user_id";
        $execute_arr = array(
            'user_id'=>$user_id,
            'status_from'=>$status_from,
            'set_to_status'=>$set_to_status,
        );
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
    }
}
?>