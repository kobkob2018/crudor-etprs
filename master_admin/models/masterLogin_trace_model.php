<?php
  class MasterLogin_trace extends TableModel{

    protected static $main_table = 'login_trace';

    protected static $users_details = array();

    public static function add_users_details($trace_list){
        foreach($trace_list as $item_key=>$item){
            print_r_help($item);
            $user_id = $item['user_id'];
            if(!isset(self::$users_details[$user_id])){
                $user_filter = array('id'=>$user_id);
                self::$users_details[$user_id] = self::simple_find_by_table_name($user_filter,'users','full_name');
            }
            $trace_list[$item_key]['user'] = self::$users_details[$user_id];
        }
        return $trace_list;
    }
}
?>