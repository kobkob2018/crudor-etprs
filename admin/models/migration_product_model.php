<?php
  class Migration_product extends TableModel{

    private static $ilbiz_db = NULL;

    protected static $main_table = 'migration_product';

    protected static function getIlbizDb() {
        if (!isset(self::$ilbiz_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$ilbiz_db = new PDO('mysql:host='.get_config('ilbiz_db_host').'; port='.get_config('ilbiz_db_port').'; dbname='.get_config('ilbiz_db_db').'', get_config('ilbiz_db_user'), get_config('ilbiz_db_pass'), $pdo_options);      
          }
        return self::$ilbiz_db;
    }

    public static function check_if_migration_exist($filter){
        $db = DB::getInstance();
        $check_true = false;
        $check_tables = array("migration_product","migration_product_cat","migration_product_sub","	migration_product_image");
        foreach($check_tables as $table){
            if($check_true){
                continue;
            }
            $sql = "SELECT site_id FROM $table WHERE site_id = :site_id LIMIT 1";
            $req = $db->prepare($sql);
            $req->execute($filter);
            $result = $req->fetch();
            if($result){
                $check_true = true;
            }
        }
        return $check_true;
    }

    protected static function create_product_dir($site_id){
        if(!is_dir('assets_s/'.$site_id)){
            $oldumask = umask(0) ;
            $mkdir = @mkdir( 'assets_s/'.$site_id, 0755 ) ;
            umask( $oldumask ) ;
        }
        if(!is_dir('assets_s/'.$site_id.'/products')){
            $oldumask = umask(0) ;
            $mkdir = @mkdir( 'assets_s/'.$site_id.'/products', 0755 ) ;
            umask( $oldumask ) ;
        }
        if(!is_dir('assets_s/'.$site_id.'/product')){
            $oldumask = umask(0) ;
            $mkdir = @mkdir( 'assets_s/'.$site_id.'/product', 0755 ) ;
            umask( $oldumask ) ;
        }
        if(!is_dir('assets_s/'.$site_id.'/product/images')){
            $oldumask = umask(0) ;
            $mkdir = @mkdir( 'assets_s/'.$site_id.'/product/images', 0755 ) ;
            umask( $oldumask ) ;
        }
    }

    public static function delete_older($site_id){
        $db = DB::getInstance();
        $migration_images = self::simple_get_list_by_table_name(array('site_id'=>$site_id),'migration_product_image');
        if(!$migration_images){
            $migration_images = array();
        }
        foreach($migration_images as $migration_image){
            $image_id = $migration_image['image_id'];
            $image = self::simple_find_by_table_name(array('id'=>$image_id),'product_images');
            
            self::simple_delete_by_table_name($migration_image['id'],'migration_product_image');
            
            if(!$image){
                continue;
            }
            if($image['image'] != ""){
                $image_url = "assets_s/".$site_id."/product/images/".$image['image'];
                if(file_exists($image_url)){
                    unlink($image_url);
                }
            }
            if($image['small_image'] != ""){
                $small_image_url = "assets_s/".$site_id."/product/images/".$image['small_image'];
                if(file_exists($small_image_url)){
                    unlink($small_image_url);
                }
            }
            
            self::simple_delete_by_table_name($image['id'],'product_images');
        }

        $migration_products = self::simple_get_list_by_table_name(array('site_id'=>$site_id),'migration_product');

		if(!$migration_products){
            $migration_products = array();
        }
        foreach($migration_products as $migration_product){

            $product_id = $migration_product['product_id'];
            $product = self::simple_find_by_table_name(array('id'=>$product_id),'products');

            self::simple_delete_by_table_name($migration_product['id'],'migration_product');

            if($product){
                if($product['image'] != ""){
                    $image_url = "assets_s/".$site_id."/products/".$product['image'];
                    if(file_exists($image_url)){
                        unlink($image_url);
                    }
                }
            }
            
            self::simple_delete_by_table_name($migration_product['product_id'],'products');
        
            
            $sql = "DELETE FROM product_sub_assign WHERE product_id = :product_id";
            $req = $db->prepare($sql);
            $req->execute(array('product_id'=>$migration_product['product_id']));

        }
 
        $migration_product_subs = self::simple_get_list_by_table_name(array('site_id'=>$site_id),'migration_product_sub');
        if(!$migration_product_subs){
            $migration_product_subs = array();
        }
        foreach($migration_product_subs as $migration_product_sub){
            self::simple_delete_by_table_name($migration_product_sub['id'],'migration_product_sub');
            self::simple_delete_by_table_name($migration_product_sub['sub_id'],'product_sub');
        
            $sql = "DELETE FROM product_sub_assign WHERE sub_id = :sub_id";
            $req = $db->prepare($sql);
            $req->execute(array('sub_id'=>$migration_product_sub['sub_id']));

            $sql = "DELETE FROM product_sub_cat_assign WHERE sub_id = :sub_id";
            $req = $db->prepare($sql);
            $req->execute(array('sub_id'=>$migration_product_sub['sub_id']));
        }

        $migration_product_cats = self::simple_get_list_by_table_name(array('site_id'=>$site_id),'migration_product_cat');
        if(!$migration_product_cats){
            $migration_product_cats = array();
        }
        foreach($migration_product_cats as $migration_product_cat){
            self::simple_delete_by_table_name($migration_product_cat['id'],'migration_product_cat');
            self::simple_delete_by_table_name($migration_product_cat['cat_id'],'product_cat');
        
            $sql = "DELETE FROM product_sub_cat_assign WHERE cat_id = :cat_id";
            $req = $db->prepare($sql);
            $req->execute(array('cat_id'=>$migration_product_cat['cat_id']));
        }
    }

    public static function do_migrate($site_id,$migration_site){
        self::create_product_dir($site_id);
        //migration product subs
        $ilbiz_db = self::getIlbizDb();
        $sql = "SELECT * FROM user_products_subject WHERE unk = :unk AND deleted = '0'";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$migration_site['old_unk']));
        $subjects = $req->fetchAll();
        if(!$subjects){
            $subjects = array();
        }
        foreach($subjects as $subject){
            $new_product_cat = array(
                'label'=>utgt($subject['name']),
                'site_id'=>$site_id,
                'active'=>($subject['active'] == '0')? '1': '0'
            );
            $new_cat_id = self::simple_create_by_table_name($new_product_cat,"product_cat");
            $migration_product_cat = array(
                'cat_id'=>$new_cat_id,
                'site_id'=>$site_id,
                'unk'=>$migration_site['old_unk'],
                'old_id'=>$subject['id']
            );
            self::simple_create_by_table_name($migration_product_cat,"migration_product_cat");
        }


        //migrate products subs
        $sql = "SELECT * FROM user_products_cat WHERE unk = :unk AND deleted = '0'";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$migration_site['old_unk']));
        $product_cats = $req->fetchAll();
        if(!$product_cats){
            $product_cats = array();
        }
        foreach($product_cats as $cat){
            $old_cat = $cat['subject_id'];
            $migration_product_cat = self::simple_find_by_table_name(array('old_id'=>$old_cat),"migration_product_cat",'cat_id');
            $new_cat_id = false;
            if($migration_product_cat){
                $new_cat_id = $migration_product_cat['cat_id'];
            }
            $new_sub = array(
                'label'=>utgt($cat['name']),
                'site_id'=>$site_id,
                'active'=>($cat['status'] == '0')? '1' : '0'
            );
            $new_sub_id = self::simple_create_by_table_name($new_sub,"product_sub");
            $migration_product_sub = array(
                'sub_id'=>$new_sub_id,
                'site_id'=>$site_id,
                'unk'=>$migration_site['old_unk'],
                'old_id'=>$cat['id']
            );
            self::simple_create_by_table_name($migration_product_sub,"migration_product_sub");
            if(!$new_cat_id){
                $new_cat_id = '0';
            }
            
            $product_sub_cat_assign = array(
                'sub_id'=>$new_sub_id,
                'cat_id'=>$new_cat_id
            );
            self::simple_create_by_table_name($product_sub_cat_assign,"product_sub_cat_assign");
        }

        $images_url = "http://";
        if($migration_site['old_has_ssl'] == '1'){
            $images_url = "https://";
        }

        $images_url .= $migration_site['old_domain']."/products/";

        //migrate products
        $sql = "SELECT * FROM user_products WHERE unk = :unk AND deleted = '0'";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$migration_site['old_unk']));
        $products = $req->fetchAll();
        if(!$products){
            $products = array();
        }
        foreach($products as $product){
          
            $new_product = array(
                'label'=>utgt($product['name']),
                'title'=>utgt($product['name']),
                'list_label'=>utgt($product['name']),
                'meta_title'=>utgt($product['name']),
                'content'=>utgt($product['content']),
                'description'=>utgt($product['summary']),
                'meta_description'=>utgt($product['summary']),
                'price'=>utgt($product['price']),
                'price_special'=>$product['price_special'],
                'link'=>utgt($product['url_link']),
                'link_text'=>utgt($product['url_name']),
                'site_id'=>$site_id,
                'active'=>($product['active'] == '0')? '1' : '0',
                'priority'=>$product['place'],
                'image'=>$product['img'],
            );
            if($new_product['priority'] > 1000){
                $new_product['priority'] = '1000';
            }

            if($product['video_10service'] != ""){
                $new_product['content'].="<p></p><div class='video-container'>".utgt(stripslashes($product['video_10service']))."</div>";
            }
            try{
                $new_product_id = self::simple_create_by_table_name($new_product,"products");
            }
            catch (Exception $e) {
                echo 'Caught exception: '.  $e->getMessage();
                print_r_help($new_product);
                exit("problem here");
            }
            $migration_product = array(
                'product_id'=>$new_product_id,
                'site_id'=>$site_id,
                'unk'=>$migration_site['old_unk'],
                'old_id'=>$product['id']
            );
            self::simple_create_by_table_name($migration_product,"migration_product");
            
            //migrate products
            $sql = "SELECT * FROM user_model_cat_belong WHERE model = 'products' AND itemId = :product_id";
            $req = $ilbiz_db->prepare($sql);
            $req->execute(array('product_id'=>$product['id']));
            $assigns = $req->fetchAll();
            if(!$assigns){
                $assigns = array();
            }
            foreach($assigns as $assign){
                $old_sub = $assign['catId'];
                $migration_product_sub = self::simple_find_by_table_name(array('old_id'=>$old_sub),"migration_product_sub",'sub_id');
                if($migration_product_sub){
                    $new_sub_id = $migration_product_sub['sub_id'];
                    $product_sub_assign = array(
                        'product_id'=>$new_product_id,
                        'sub_id'=>$new_sub_id
                    );
                    self::simple_create_by_table_name($product_sub_assign,"product_sub_assign");
                }
            }

            if($product['img'] != ""){
                $image_url = $images_url.$product['img'];
                $new_image_url = "assets_s/".$site_id."/products/".$product['img'];
                if(!file_exists($new_image_url)){                  
                    file_put_contents($new_image_url, file_get_contents($image_url));
                } 
            }

            if($product['img'] != ""){
                self::do_migrate_image($images_url, $new_product_id , $product['img'], $site_id, $migration_site);
            }

            if($product['img2'] != ""){
                self::do_migrate_image($images_url, $new_product_id , $product['img2'], $site_id, $migration_site);
            }

            if($product['img3'] != ""){
                self::do_migrate_image($images_url, $new_product_id , $product['img3'], $site_id, $migration_site);
            }
        }
    }


    protected static function do_migrate_image($images_url, $product_id, $img_name,$site_id, $migration_site, $old_image_id = '0'){
        $new_image = array(
            'label'=>'',
            'site_id'=>$site_id,
            'priority'=>'10',
            'product_id'=>$product_id,
            'image'=>$img_name,
            'small_image'=>"s_".$img_name,
        );
        $new_image_id = self::simple_create_by_table_name($new_image,"product_images");
        $migration_image = array(
            'image_id'=>$new_image_id,
            'site_id'=>$site_id,
            'unk'=>$migration_site['old_unk'],
            'old_id'=>$old_image_id
        );
        self::simple_create_by_table_name($migration_image,"migration_product_image");


        $image_url = $images_url.$img_name;
        $new_image_url = "assets_s/".$site_id."/product/images/".$img_name;
        if(!file_exists($new_image_url)){                  
            file_put_contents($new_image_url, file_get_contents($image_url));
        } 

        $small_image_url = "assets_s/".$site_id."/product/images/"."s_".$img_name;
        if(!file_exists($small_image_url)){                  
            file_put_contents($small_image_url, file_get_contents($image_url));
        } 
    }
}
?>