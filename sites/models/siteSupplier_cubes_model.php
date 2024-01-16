<?php
  class SiteSupplier_cubes extends TableModel{

    protected static $main_table = 'supplier_cubes';

    public static $assets_mapping = array(
        'cube_image'=>'s_cubes',
        'cube_amin'=>'s_cubes'
    );

    public static function get_cat_supplier_cubes($cat_id){
        $cat_filter_sql = "cat_id = $cat_id";
        return self::get_cat_filtered_supplier_cubes($cat_filter_sql);
    }

    public static function get_cat_tree_supplier_cubes($cat_tree){
        $cat_in_sql_arr = array();
        foreach($cat_tree as $cat){
            $cat_in_sql_arr[] = $cat['id'];
        }

        if(empty($cat_in_sql_arr)){
            return false;
        }
        $cat_in_sql = implode(",",$cat_in_sql_arr);
        $cat_filter_sql = "cat_id IN ($cat_in_sql)";
        return self::get_cat_filtered_supplier_cubes($cat_filter_sql);
    }

    protected static function get_cat_filtered_supplier_cubes($cat_filter_sql){
        $db = Db::getInstance();

        $sql = "SELECT uls.user_id FROM user_lead_settings uls 
                LEFT JOIN user_lead_visability ulv ON uls.user_id = ulv.user_id 
                WHERE uls.active = '1' 
                
                AND  (uls.end_date > now() OR uls.end_date = 0000-00-00) 
                AND ulv.show_in_sites = '1' 
                AND uls.user_id IN(
                        SELECT distinct user_id FROM user_cat WHERE $cat_filter_sql)";
        	
        $req = $db->prepare($sql);
        $req->execute();
        $users = $req->fetchAll();
        $user_id_in_arr = array();
        foreach($users as $user){
            $user_id_in_arr[] = $user['user_id']; 
        }
        if(empty($user_id_in_arr)){
            return false;
        }
        $user_id_in_str = implode(",",$user_id_in_arr);
        $sql = "SELECT * FROM supplier_cubes WHERE status != '0' AND user_id IN($user_id_in_str)"; 
        
        $req = $db->prepare($sql);
        $req->execute();
        $cubes = $req->fetchAll(); 
        return $cubes;
    }

    public static function add_count_to_cube($cube_id, $column_name){
        $execute_arr = array('cube_id'=>$cube_id);
        $sql = "UPDATE supplier_cubes SET $column_name = $column_name + 1 WHERE id = :cube_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        return;
    }

  }
?>