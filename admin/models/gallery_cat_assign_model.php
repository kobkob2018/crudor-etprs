<?php
  class gallery_cat_assign extends TableModel{

    protected static $main_table = 'gallery_cat_assign';

    public static function get_gallery_assigned_cats($gallery_id){
        $db = Db::getInstance();
        $sql = "SELECT cat_id FROM gallery_cat_assign WHERE gallery_id = :gallery_id";
        $req = $db->prepare($sql);
        $req->execute(array('gallery_id'=>$gallery_id));
        $cat_list = $req->fetchAll();
        $return_options = array();
        foreach($cat_list as $cat_arr){
            $return_options[] = $cat_arr['cat_id'];
        }
        return $return_options;
    }

    public static function assign_cats_to_gallery($gallery_id,$assign_cats){
        $db = Db::getInstance();
        $sql = "DELETE FROM gallery_cat_assign WHERE gallery_id = :gallery_id";
        $req = $db->prepare($sql);
        $req->execute(array('gallery_id'=>$gallery_id));
        foreach($assign_cats as $cat_id=>$checked){
            $sql = "INSERT INTO gallery_cat_assign(cat_id,gallery_id) VALUES(:cat_id,:gallery_id)";
            $req = $db->prepare($sql);
            $req->execute(array('gallery_id'=>$gallery_id,'cat_id'=>$cat_id));
        }
    }

}
?>