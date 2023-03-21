<?php

//this one is used at the cities controller
//in the cats controller the cat_city model is used
    class Quote_cat_assign extends TableModel{

        protected static $main_table = 'quote_cat_assign';

        public static function get_assigned_cats_to_item($item_id){
            $filter_arr = array('quote_id'=>$item_id);
            $assigned_list = self::get_list($filter_arr,'cat_id');
            if(!$assigned_list){
                return array();
            }
            return $assigned_list;
        }

        public static function assign_cats_to_item($item_id, $cat_id_arr){

            $sql_arr = array(
                'quote_id'=>$item_id
            );
            $db = Db::getInstance();

            $sql = "DELETE FROM quote_cat_assign WHERE quote_id = :quote_id"; 		
            $req = $db->prepare($sql);
            $req->execute($sql_arr);


            if(empty($cat_id_arr)){
                return;
            }
            $cat_insert_arr = array();
            foreach($cat_id_arr as $cat_id){
                $cat_insert_arr[] = "(:quote_id, $cat_id)";
            }

            $cat_insert_str = implode(",",$cat_insert_arr);

            $sql = "INSERT INTO quote_cat_assign(quote_id , cat_id) VALUES $cat_insert_str "; 
            $req = $db->prepare($sql);
            $req->execute($sql_arr);
            return;
        }
    }
?>