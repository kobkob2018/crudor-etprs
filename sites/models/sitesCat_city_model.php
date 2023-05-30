<?php

//this one is used at the categories controller
//in the cities controller the city_cat model is used
  class SitesCat_city extends TableModel{

    protected static $main_table = 'cat_city';


    public static function get_item_city_list($item_id){
        $filter_arr = array("cat_id"=>$item_id);
        return self::simple_get_list($filter_arr);
    }

    public static function get_cat_city_assign($cat_tree){
        $city_list = false;
        foreach($cat_tree as $cat){
            $cat_city_list = self::get_item_city_list($cat['id']);
            if($cat_city_list){
                $city_list = $cat_city_list;
            }
        }
        if(!$city_list){
            return false;
        }
        $city_id_arr = array();
        foreach($city_list as $city){
            $city_id_arr[] = $city['city_id'];
        }
        return $city_id_arr;
    }

}
?>