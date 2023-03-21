<?php
  class SiteSupplier_cubes extends TableModel{

    protected static $main_table = 'supplier_cubes';

    public static $assets_mapping = array(
        'cube_image'=>'s_cubes',
        'cube_amin'=>'s_cubes'
    );

    public static function get_cat_tree_supplier_cubes($cat_tree){
        $db = Db::getInstance();
        $cat_in_sql_arr = array();
        foreach($cat_tree as $cat){
            $cat_in_sql_arr[] = $cat['id'];
        }

        if(empty($cat_in_sql_arr)){
            return false;
        }
        $cat_in_sql = implode(",",$cat_in_sql_arr);

        $sql = "SELECT uls.user_id FROM user_lead_settings uls 
                LEFT JOIN user_lead_visability ulv ON uls.user_id = ulv.user_id 
                WHERE uls.active = '1' 
                
                AND  (uls.end_date > now() OR uls.end_date = '0000-00-00') 
                AND ulv.show_in_sites = '1' 
                AND uls.user_id IN(
                        SELECT distinct user_id FROM user_cat WHERE cat_id IN ($cat_in_sql))";
        	
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



  }
?>