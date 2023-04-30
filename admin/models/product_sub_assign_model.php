<?php

    class Product_sub_assign extends TableModel{

        protected static $main_table = 'product_sub_assign';

        public static function get_assigned_subs_to_item($item_id){
            $filter_arr = array('product_id'=>$item_id);
            $assigned_list = self::get_list($filter_arr,'sub_id');
            if(!$assigned_list){
                return array();
            }
            return $assigned_list;
        }

        public static function assign_subs_to_item($item_id, $sub_id_arr){

            $sql_arr = array(
                'product_id'=>$item_id
            );
            $db = Db::getInstance();

            $sql = "DELETE FROM product_sub_assign WHERE product_id = :product_id"; 		
            $req = $db->prepare($sql);
            $req->execute($sql_arr);


            if(empty($sub_id_arr)){
                return;
            }
            $sub_insert_arr = array();
            foreach($sub_id_arr as $sub_id){
                $sub_insert_arr[] = "(:product_id, $sub_id)";
            }

            $sub_insert_str = implode(",",$sub_insert_arr);

            $sql = "INSERT INTO product_sub_assign(product_id , sub_id) VALUES $sub_insert_str "; 
            $req = $db->prepare($sql);
            $req->execute($sql_arr);
            return;
        }
    }
?>