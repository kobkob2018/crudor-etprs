<?php
  class Net_message_cat extends TableModel{

    protected static $main_table = 'net_message_cat';

    public static function get_item_cat_list($item_id){
        $filter_arr = array("message_id"=>$item_id);
        return self::simple_get_list($filter_arr);
    }

    public static function assign_cats_to_item($item_id, $cat_id_arr){
        $sql_arr = array(
            'message_id'=>$item_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM net_message_cat WHERE message_id = :message_id"; 		
        $req = $db->prepare($sql);
        $req->execute($sql_arr);


        if(empty($cat_id_arr)){
            return;
        }
        $cat_insert_arr = array();
        foreach($cat_id_arr as $cat_id=>$on){
            $cat_insert_arr[] = "(:message_id, $cat_id)";
        }

        $cat_insert_str = implode(",",$cat_insert_arr);

        $sql = "INSERT INTO net_message_cat(message_id , cat_id) VALUES $cat_insert_str "; 
        $req = $db->prepare($sql);
        $req->execute($sql_arr);
        return;
    }

    public static $tree_select_info = array(
        'alias'=>'cat',
        'table'=>'net_message_cat',
        'assign_1'=>'cat_id',
        'assign_2'=>'message_id'
    );

    public static function get_cat_users($cat_list){
        $cat_id_list = array();
        foreach($cat_list as $cat){
            $cat_id_list = self::add_cat_id_to_list($cat['cat_id'], $cat_id_list);
            $cat_offspring = Biz_categories::simple_get_item_offsprings($cat['cat_id'],'id, parent');
            foreach($cat_offspring as $cat_offspring){
                $cat_id_list = self::add_cat_id_to_list($cat_offspring['id'], $cat_id_list);
            }
        }
        if(empty($cat_id_list)){
            return array();
        }


        
        $cat_id_in = implode(", ",$cat_id_list);
        $sql = "SELECT user.id, user.email, user.full_name 
                FROM users user
                LEFT JOIN user_lead_settings uls ON uls.user_id = user.id 
                WHERE user.id IN(SELECT distinct user_id FROM user_cat WHERE cat_id IN ($cat_id_in)) 
                AND user.active = '1' 
                AND uls.active = '1' 
                AND  (uls.end_date > now() OR uls.end_date = 0000-00-00  OR uls.end_date IS NULL)";
        $db = Db::getInstance();
        $req = $db->prepare($sql);
        $req->execute();
        $user_list = $req->fetchAll();
        return $user_list;
    }

    public static function add_cat_id_to_list($cat_id,$cat_id_list){
        if(!in_array($cat_id,$cat_id_list)){
            $cat_id_list[] = $cat_id;
        }
        return $cat_id_list;
    }
}
?>