<?php
  class User_cat extends TableModel{

    protected static $main_table = 'user_cat';


    public static function get_item_cat_list($item_id){
        $filter_arr = array("user_id"=>$item_id);
        return self::simple_get_list($filter_arr);
    }

    public static function assign_cats_to_item($item_id, $cat_id_arr){

        $sql_arr = array(
            'user_id'=>$item_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM user_cat WHERE user_id = :user_id"; 		
        $req = $db->prepare($sql);
        $req->execute($sql_arr);


        if(empty($cat_id_arr)){
            return;
        }
        $cat_insert_arr = array();
        foreach($cat_id_arr as $cat_id=>$on){
            $cat_insert_arr[] = "(:user_id, $cat_id)";
        }

        $cat_insert_str = implode(",",$cat_insert_arr);

        $sql = "INSERT INTO user_cat(user_id , cat_id) VALUES $cat_insert_str "; 
        $req = $db->prepare($sql);
        $req->execute($sql_arr);
        return;
    }

    public static function delete_item_assignments($item_id){
        $sql_arr = array(
            'user_id'=>$item_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM user_cat WHERE user_id = :user_id";
        $req = $db->prepare($sql);
        $req->execute($sql_arr);       
    }

    public static $tree_select_info = array(
        'alias'=>'cat',
        'table'=>'user_cat',
        'assign_1'=>'cat_id',
        'assign_2'=>'user_id'
    );

}
?>