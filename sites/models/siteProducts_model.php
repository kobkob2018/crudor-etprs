<?php
  class SiteProducts extends TableModel{

    protected static $main_table = 'products';

    public static $assets_mapping = array(
        'product_image'=>'products',
        'product_images'=>'product/images',
    );

    public static function search_by_str($search){
        $current_site = Sites::get_current_site();
        $execute_arr = array(
            'site_id'=>$current_site['id'], 
            'search'=>"%".$search."%");
        $db = Db::getInstance();
        $sql = "SELECT * FROM 
                    products WHERE (
                            title LIKE :search 
                            OR description LIKE :search 
                            OR description LIKE :search
                        ) 
                        AND site_id = :site_id 
                        AND active = '1'";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $product_list = $req->fetchAll();
        return $product_list;
    }

    public static function get_product_images($product_id){
        $execute_arr = array('product_id'=>$product_id);
        $db = Db::getInstance();
        $sql = "SELECT * FROM product_images WHERE product_id = :product_id";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $image_list = $req->fetchAll();
        return $image_list;
    }    

    public static function get_more_products($product_id,$limit = '4'){
        $execute_arr = array('product_id'=>$product_id);
        $db = Db::getInstance();
        $sql = "SELECT * 
                FROM products 
                WHERE id IN 
                    (SELECT DISTINCT product_id 
                    FROM product_sub_assign 
                    WHERE sub_id IN 
                        (SELECT sub_id 
                        FROM product_sub_assign 
                        WHERE product_id = :product_id) 
                    AND product_id != :product_id) 
                LIMIT $limit";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $product_list = $req->fetchAll();
        return $product_list;
    } 

  }
?>