<?php
  class SiteNet_banners extends TableModel{

    protected static $main_table = 'net_banners';

    public static $assets_mapping = array(
        'net_banners'=>'net/banners'
    );

    public static function add_count_to_banner($banner_id, $column_name){
        $execute_arr = array('banner_id'=>$banner_id);
        $sql = "UPDATE net_banners SET $column_name = $column_name + 1 WHERE id = :banner_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        return;
    }

    public static function get_cat_tree_net_banners($cat_tree){
        $db = Db::getInstance();
        $cat_in_sql_arr = array();
        foreach($cat_tree as $cat){
            $cat_in_sql_arr[] = $cat['id'];
        }

        if(empty($cat_in_sql_arr)){
            return false;
        }
        $cat_in_sql = implode(",",$cat_in_sql_arr);
        $sql = "SELECT * FROM net_banners 
        WHERE active = '1' AND deleted = '0' AND (max_views > views OR max_views = '0' )  
        AND  id IN(
                SELECT distinct banner_id FROM net_banner_cat WHERE cat_id IN ($cat_in_sql))";

        $req = $db->prepare($sql);
        $req->execute();
        $banners = $req->fetchAll();
        return $banners;
    }

}
?>