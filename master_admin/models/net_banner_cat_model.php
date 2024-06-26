<?php
  class Net_banner_cat extends TableModel{

    protected static $main_table = 'net_banner_cat';

    public static function get_item_cat_list($item_id){
        $filter_arr = array("banner_id"=>$item_id);
        return self::simple_get_list($filter_arr);
    }

    public static function assign_cats_to_item($item_id, $cat_id_arr){
        $sql_arr = array(
            'banner_id'=>$item_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM net_banner_cat WHERE banner_id = :banner_id"; 		
        $req = $db->prepare($sql);
        $req->execute($sql_arr);


        if(empty($cat_id_arr)){
            return;
        }
        $cat_insert_arr = array();
        foreach($cat_id_arr as $cat_id=>$on){
            $cat_insert_arr[] = "(:banner_id, $cat_id)";
        }

        $cat_insert_str = implode(",",$cat_insert_arr);

        $sql = "INSERT INTO net_banner_cat(banner_id , cat_id) VALUES $cat_insert_str "; 
        $req = $db->prepare($sql);
        $req->execute($sql_arr);
        return;
    }

    public static function delete_item_assignments($item_id){
        $sql_arr = array(
            'banner_id'=>$item_id
        );
        $db = Db::getInstance();

        $sql = "DELETE FROM net_banner_cat WHERE banner_id = :banner_id";
        $req = $db->prepare($sql);
        $req->execute($sql_arr);       
    }


    public static $tree_select_info = array(
        'alias'=>'cat',
        'table'=>'net_banner_cat',
        'assign_1'=>'cat_id',
        'assign_2'=>'banner_id',
        'payload'=>array('order_by'=>'label'),
    );


}
?>