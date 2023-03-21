<?php
  class User_city extends TableModel{

    protected static $main_table = 'user_city';


    public static function get_item_city_list($item_id){
        $filter_arr = array("user_id"=>$item_id);
        return self::simple_get_list($filter_arr);
    }

    public static function assign_cities_to_item($item_id, $city_id_arr){

        $sql_arr = array(
            'user_id'=>$item_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM user_city WHERE user_id = :user_id"; 		
        $req = $db->prepare($sql);
        $req->execute($sql_arr);


        if(empty($city_id_arr)){
            return;
        }
        $city_insert_arr = array();
        foreach($city_id_arr as $city_id=>$on){
            $city_insert_arr[] = "(:user_id, $city_id)";
        }

        $city_insert_str = implode(",",$city_insert_arr);

        $sql = "INSERT INTO user_city(user_id , city_id) VALUES $city_insert_str "; 
        $req = $db->prepare($sql);
        $req->execute($sql_arr);
        return;
    }

    public static function delete_item_assignments($item_id){
        $sql_arr = array(
            'user_id'=>$item_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM user_city WHERE user_id = :user_id";
        $req = $db->prepare($sql);
        $req->execute($sql_arr);       
    }

    public static $tree_select_info = array(
        'alias'=>'city',
        'table'=>'user_city',
        'assign_1'=>'city_id',
        'assign_2'=>'user_id'
    );

}
?>