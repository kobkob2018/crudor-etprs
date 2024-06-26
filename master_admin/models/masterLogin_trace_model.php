<?php
  class MasterLogin_trace extends TableModel{

    protected static $main_table = 'login_trace';

    protected static $users_details = array();

    public static function add_users_details($trace_list){
        foreach($trace_list as $item_key=>$item){
            $user_id = $item['user_id'];
            if(!isset(self::$users_details[$user_id])){
                $user_filter = array('id'=>$user_id);
                self::$users_details[$user_id] = self::simple_find_by_table_name($user_filter,'users','full_name');
            }
            $trace_list[$item_key]['user'] = self::$users_details[$user_id];
        }
        return $trace_list;
    }

    public static function clear_old_logins(){
        $db = DB::getInstance();
        $sql = "DELETE FROM login_trace WHERE login_time < NOW() - interval 11 day";
        $req = $db->prepare($sql);
        $req->execute();
    }
}
?>