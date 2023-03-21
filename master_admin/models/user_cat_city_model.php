<?php
  class User_cat_city extends TableModel{

    protected static $main_table = 'user_cat_city';


    public static function get_item_cat_city_list($item_id, $cat_id){
        $filter_arr = array("user_id"=>$item_id, "cat_id"=>$cat_id);
        return self::simple_get_list($filter_arr);
    }

    public static function assign_cities_cats_and_item($item_id,$cat_id, $city_id_arr){

        $sql_arr = array(
            'user_id'=>$item_id,
            'cat_id'=>$cat_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM user_cat_city WHERE user_id = :user_id AND cat_id = :cat_id"; 		
        $req = $db->prepare($sql);
        $req->execute($sql_arr);


        if(empty($city_id_arr)){
            return;
        }
        $city_insert_arr = array();
        foreach($city_id_arr as $city_id=>$on){
            $city_insert_arr[] = "(:user_id, :cat_id, $city_id)";
        }

        $city_insert_str = implode(",",$city_insert_arr);

        $sql = "INSERT INTO user_cat_city(user_id ,cat_id, city_id) VALUES $city_insert_str "; 
        $req = $db->prepare($sql);
        $req->execute($sql_arr);
        return;
    }

    public static function delete_cat_and_item_assignments($item_id, $cat_id){
        $sql_arr = array(
            'user_id'=>$item_id,
            "cat_id"=>$cat_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM user_cat_city WHERE user_id = :user_id AND cat_id = :cat_id";
        $req = $db->prepare($sql);
        $req->execute($sql_arr);       
    }

    public static $tree_select_info = array(
        'alias'=>'cat_city',
        'table'=>'user_cat_city',
        'assign_1'=>'city_id',
        'assign_2'=>'cat_id',
        'assign_3'=>'user_id'
    );

}
?>