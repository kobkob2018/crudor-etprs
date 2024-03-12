<?php
  class siteGallery extends TableModel{

    protected static $main_table = 'gallery';

    public static function get_site_cat_list($site_id){
        $execute_arr = array('site_id'=>$site_id);
        $sql = "SELECT * FROM gallery_cat WHERE site_id = :site_id AND active = '1'";  
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $result = $req->fetchAll();
        return $result;
    }

    public static function get_cat_gallery_list($cat_id,$site_id){
        $execute_arr = array('cat_id'=>$cat_id,'site_id'=>$site_id);
        $sql = "SELECT gal.* FROM gallery_cat_assign assign LEFT JOIN gallery gal ON gal.id = assign.gallery_id WHERE assign.cat_id = :cat_id AND gal.active = '1' AND gal.site_id = :site_id";  
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $result = $req->fetchAll();
        return $result;
    }

    public static function get_gallery_images($gallery_id,$site_id = false){
        $execute_arr = array('gallery_id'=>$gallery_id);
        $site_id_sql = "";
        if($site_id){
            $site_id_sql = " AND site_id = :site_id ";
            $execute_arr['site_id'] = $site_id;
        }
        $sql = "SELECT * FROM gallery_images WHERE gallery_id = :gallery_id $site_id_sql ORDER BY priority, id";  
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $result = $req->fetchAll();
        return $result;
    }

    public static $assets_mapping = array(
        'gallery_images'=>'gallery'
    );

}
?>