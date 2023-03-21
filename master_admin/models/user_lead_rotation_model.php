<?php
  class User_lead_rotation extends TableModel{

    protected static $main_table = 'user_lead_rotation';

    public static function create_for_user($user_id){
        $filter_arr = array('user_id'=>$user_id);
        $sql = "SELECT id FROM  user_lead_rotation WHERE user_id = :user_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($filter_arr);
        $result_row = $req->fetch();
        if($result_row){
            return $result_row['id'];
        }
        $sql = "INSERT INTO user_lead_rotation(user_id) VALUES(:user_id)";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($filter_arr);
        return $db->lastInsertId();
    }
}
?>