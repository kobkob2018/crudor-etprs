<?php
  class Net_messages extends TableModel{

    protected static $main_table = 'net_messages';

    protected static $auto_delete_from_attached_tables = array(
        'net_message_user_read'=>array(
            'table'=>'net_message_user_read',
            'id_key'=>'message_id'
        ),
        'net_message_cat'=>array(
            'table'=>'net_message_cat',
            'id_key'=>'message_id'
        ),
    ); 

    public static $fields_collection = array(

        'label'=>array(
            'label'=>'שם לזיהוי',
            'type'=>'text',
            'validation'=>'required'
        ),

        'title'=>array(
            'label'=>'כותרת',
            'type'=>'text',
            'validation'=>'required'
        ),

        'status'=>array(
            'label'=>'סטטוס',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא פעיל'),
                array('value'=>'1', 'title'=>'נוצר'),
                array('value'=>'2', 'title'=>'נשלח'),
            )
        ),

        'msg'=>array(
            'label'=>'מסר',
            'type'=>'textbox',
            'reachtext'=>true,
            'css_class'=>'big-text'
        ),

    );

    public static function add_user_read_message($user_id,$message_id){
        $sql = "INSERT INTO net_message_user_read(user_id,message_id) VALUES(:user_id,:message_id)";
        $execute_arr = array(
            'user_id'=>$user_id,
            'message_id'=>$message_id
        );
        $db = Db::getInstance();
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
    }

    public static function update_send_status($message_id,$send_count){
        $sql = "UPDATE net_messages SET status = '2', send_count = :send_count, last_time_sent = now() WHERE id = :message_id";
        $execute_arr = array(
            'message_id'=>$message_id,
            'send_count'=>$send_count
        );
        $db = Db::getInstance();
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
    }

}
?>