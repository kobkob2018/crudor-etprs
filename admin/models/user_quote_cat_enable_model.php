<?php

    class User_quote_cat_enable extends TableModel{

        protected static $main_table = 'user_quote_cat_enable';

        public static function get_assigned_cats_to_item($item_id){
            $filter_arr = array('user_id'=>$item_id);
            $assigned_list = self::get_list($filter_arr,'cat_id');
            if(!$assigned_list){
                return array();
            }
            return $assigned_list;
        }

        public static function assign_cats_to_item($item_id, $cat_id_arr){

            $sql_arr = array(
                'user_id'=>$item_id
            );
            $db = Db::getInstance();

            $sql = "DELETE FROM user_quote_cat_enable WHERE user_id = :user_id"; 		
            $req = $db->prepare($sql);
            $req->execute($sql_arr);


            if(empty($cat_id_arr)){
                return;
            }
            $cat_insert_arr = array();
            foreach($cat_id_arr as $cat_id){
                $cat_insert_arr[] = "(:user_id, $cat_id)";
            }

            $cat_insert_str = implode(",",$cat_insert_arr);

            $sql = "INSERT INTO user_quote_cat_enable(user_id , cat_id) VALUES $cat_insert_str "; 
            $req = $db->prepare($sql);
            $req->execute($sql_arr);
            return;
        }

        public static function add_item_to_cat($item_id, $cat_id){
            $filter_arr = array('user_id'=>$item_id,'cat_id'=>$cat_id);
            $assign = self::find($filter_arr,'cat_id');
            if($assign){
                return;
            }
            $fixed_values = $filter_arr;
            return self::create($fixed_values); 
        }

    }
?>