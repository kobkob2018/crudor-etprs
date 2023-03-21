<?php

//this one is used at the cities controller
//in the cats controller the cat_city model is used
  class City_cat extends TableModel{

    protected static $main_table = 'cat_city';


    public static function get_item_city_list($item_id){
        $filter_arr = array("city_id"=>$item_id);
        return self::simple_get_list($filter_arr);
    }

    public static function assign_cats_to_item($item_id, $cat_id_arr){

        $sql_arr = array(
            'city_id'=>$item_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM cat_city WHERE city_id = :city_id"; 		
        $req = $db->prepare($sql);
        $req->execute($sql_arr);


        if(empty($cat_id_arr)){
            return;
        }
        $cat_insert_arr = array();
        foreach($cat_id_arr as $cat_id=>$on){
            $cat_insert_arr[] = "(:city_id, $cat_id)";
        }

        $cat_insert_str = implode(",",$cat_insert_arr);

        $sql = "INSERT INTO cat_city(city_id , cat_id) VALUES $cat_insert_str "; 
        $req = $db->prepare($sql);
        $req->execute($sql_arr);
        return;
    }

    public static function delete_item_assignments($item_id){
        $sql_arr = array(
            'city_id'=>$item_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM cat_city WHERE city_id = :city_id";
        $req = $db->prepare($sql);
        $req->execute($sql_arr);       
    }

    public static $tree_select_info = array(
        'alias'=>'cat',
        'table'=>'cat_city',
        'assign_1'=>'cat_id',
        'assign_2'=>'city_id'
    );

}
?>