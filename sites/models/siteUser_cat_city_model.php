<?php

//this one is used at the categories controller
//in the cities controller the city_cat model is used
  class SiteUser_cat_city extends TableModel{

    protected static $main_table = 'user_cat_city';


    public static function get_item_city_list($item_id){
        $filter_arr = array("cat_id"=>$item_id);
        return self::simple_get_list($filter_arr);
    }

    public static function get_cat_city_assign($cat_id){
        $db = DB::getInstance();
        $filter_arr = array('cat_id'=>$cat_id);
        $city_list = array();
        $sql = "SELECT * FROM user_city WHERE user_id IN (SELECT user_id FROM user_cat WHERE cat_id = :cat_id)";
        $req = $db->prepare($sql);
        $req->execute($filter_arr);
        $user_city_list = $req->fetchAll();
        if($user_city_list){
            foreach($user_city_list as $city){
                $city_list[] = $city['city_id'];
            }
        }

        $sql = "SELECT * FROM user_cat_city WHERE cat_id = :cat_id AND user_id IN (SELECT user_id FROM user_cat WHERE cat_id = :cat_id)";
        $req = $db->prepare($sql);
        $req->execute($filter_arr);
        $user_cat_city_list = $req->fetchAll();
        if($user_cat_city_list){
            foreach($user_cat_city_list as $city){
                $city_list[] = $city['city_id'];
            }
        }

        return $city_list;
    }

}
?>