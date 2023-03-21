<?php

//this one is used at the categories controller
//in the cities controller the city_cat model is used
  class Cat_city extends TableModel{

    protected static $main_table = 'cat_city';


    public static function get_item_city_list($item_id){
        $filter_arr = array("cat_id"=>$item_id);
        return self::simple_get_list($filter_arr);
    }

    public static function assign_cities_to_item($item_id, $city_id_arr){

        $sql_arr = array(
            'cat_id'=>$item_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM cat_city WHERE cat_id = :cat_id"; 		
        $req = $db->prepare($sql);
        $req->execute($sql_arr);


        if(empty($city_id_arr)){
            return;
        }
        $city_insert_arr = array();
        foreach($city_id_arr as $city_id=>$on){
            $city_insert_arr[] = "(:cat_id, $city_id)";
        }

        $city_insert_str = implode(",",$city_insert_arr);

        $sql = "INSERT INTO cat_city(cat_id , city_id) VALUES $city_insert_str "; 
        $req = $db->prepare($sql);
        $req->execute($sql_arr);
        return;
    }

    public static function delete_item_assignments($item_id){
        $sql_arr = array(
            'cat_id'=>$item_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM cat_city WHERE cat_id = :cat_id";
        $req = $db->prepare($sql);
        $req->execute($sql_arr);       
    }

    public static $tree_select_info = array(
        'alias'=>'city',
        'table'=>'cat_city',
        'assign_1'=>'city_id',
        'assign_2'=>'cat_id'
    );

}
?>