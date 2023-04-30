<?php

    class Product_sub_cat_assign extends TableModel{

        protected static $main_table = 'product_sub_cat_assign';

        public static function get_assigned_cats_to_item($item_id){
            $filter_arr = array('sub_id'=>$item_id);
            $assigned_list = self::get_list($filter_arr,'cat_id');
            if(!$assigned_list){
                return array();
            }
            return $assigned_list;
        }

        public static function assign_cats_to_item($item_id, $cat_id_arr){

            $sql_arr = array(
                'sub_id'=>$item_id
            );
            $db = Db::getInstance();

            $sql = "DELETE FROM product_sub_cat_assign WHERE sub_id = :sub_id"; 		
            $req = $db->prepare($sql);
            $req->execute($sql_arr);


            if(empty($cat_id_arr)){
                return;
            }
            $cat_insert_arr = array();
            foreach($cat_id_arr as $cat_id){
                $cat_insert_arr[] = "(:sub_id, $cat_id)";
            }

            $cat_insert_str = implode(",",$cat_insert_arr);

            $sql = "INSERT INTO product_sub_cat_assign(sub_id , cat_id) VALUES $cat_insert_str "; 
            $req = $db->prepare($sql);
            $req->execute($sql_arr);
            return;
        }
    }
?>