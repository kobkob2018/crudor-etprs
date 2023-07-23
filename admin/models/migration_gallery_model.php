<?php
  class Migration_gallery extends TableModel{

    private static $ilbiz_db = NULL;

    protected static $main_table = 'migration_gallery';

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
        $check_tables = array("migration_gallery","migration_gallery_cat","	migration_gallery_image");
        foreach($check_tables as $table){
            if($check_true){
                continue;
            }
            $sql = "SELECT label, parent FROM $table WHERE site_id = :site_id LIMIT 1";
            $req = $db->prepare($sql);
            $req->execute($filter);
            $result = $req->fetch();
            if($result){
                $check_true = true;
            }
        }
        return $check_true;
    }

    protected static function create_gallery_dir($site_id){
        if(!is_dir('assets_s/'.$site_id)){
            $oldumask = umask(0) ;
            $mkdir = @mkdir( 'assets_s/'.$site_id, 0755 ) ;
            umask( $oldumask ) ;
        }
        if(!is_dir('assets_s/'.$site_id.'/gallery')){
            $oldumask = umask(0) ;
            $mkdir = @mkdir( 'assets_s/'.$site_id.'/gallery', 0755 ) ;
            umask( $oldumask ) ;
        }
    }

    public static function delete_older($site_id){
        $db = DB::getInstance();
        $migration_images = self::simple_find_by_table_name(array('site_id'=>$site_id),'migration_gallery_image');
        if(!$migration_images){
            $migration_images = array();
        }
        foreach($migration_images as $migration_image){
            $image_id = $migration_image['image_id'];
            $image = self::simple_find_by_table_name(array('id'=>$image_id),'gallery_images');
            
            self::simple_delete_by_table_name($migration_image['id'],'migration_gallery_image');
            
            if(!$image){
                continue;
            }
            if($image['image'] != ""){
                $image_url = "assets_s/".$site_id."/gallery/".$image['image'];
                if(file_exists($image_url)){
                    unlink($image_url);
                }
            }
            if($image['small_image'] != ""){
                $small_image_url = "assets_s/".$site_id."/gallery/".$image['small_image'];
                if(file_exists($small_image_url)){
                    unlink($small_image_url);
                }
            }
            
            self::simple_delete_by_table_name($image['id'],'gallery_images');
        }

        $migration_galleries = self::simple_find_by_table_name(array('site_id'=>$site_id),'migration_gallery');
        if(!$migration_galleries){
            $migration_galleries = array();
        }
        foreach($migration_galleries as $migration_gallery){
            self::simple_delete_arr_by_table_name($migration_gallery['id'],'migration_gallery');
            self::simple_delete_arr_by_table_name($migration_gallery['gallery_id'],'gallery');
        
            
            $sql = "DELETE FROM gallery_cat_assign WHERE gallery_id = :gallery_id";
            $req = $db->prepare($sql);
            $req->execute(array('gallery_id'=>$migration_gallery['gallery_id']));

        }
 
        $migration_gallery_cats = self::simple_find_by_table_name(array('site_id'=>$site_id),'migration_gallery_cat');
        if(!$migration_gallery_cats){
            $migration_gallery_cats = array();
        }
        foreach($migration_gallery_cats as $migration_gallery_cat){
            self::simple_delete_arr_by_table_name($migration_gallery_cat['id'],'migration_gallery_cat');
            self::simple_delete_arr_by_table_name($migration_gallery_cat['cat_id'],'gallery_cat');
        
            $sql = "DELETE FROM gallery_cat_assign WHERE cat_id = :cat_id";
            $req = $db->prepare($sql);
            $req->execute(array('gallery_id'=>$migration_gallery_cat['cat_id']));
        }
    }

    public static function do_migrate($site_id,$migration_site){
        self::create_gallery_dir($site_id);
        //migration gallery cats
        $ilbiz_db = self::getIlbizDb();
        $sql = "SELECT * FROM user_images_cat_subject WHERE unk = :unk AND deleted = '0'";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$migration_site['old_unk']));
        $subjects = $req->fetchAll();
        if(!$subjects){
            $subjects = array();
        }
        foreach($subjects as $subject){
            $new_gallery_cat = array(
                'label'=>utgt($subject['name']),
                'site_id'=>$site_id,
                'active'=>$subject['active']
            );
            $new_cat_id = self::simple_create_by_table_name($new_gallery_cat,"gallery_cat");
            $migration_gallery_cat = array(
                'cat_id'=>$new_cat_id,
                'site_id'=>$site_id,
                'unk'=>$migration_site['old_unk'],
                'old_id'=>$subject['id']
            );
            self::simple_create_by_table_name($migration_gallery_cat,"migration_gallery_cat");
        }
        //migrate galleries
        $sql = "SELECT * FROM user_gallery_cat WHERE unk = :unk AND deleted = '0'";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$migration_site['old_unk']));
        $galleries = $req->fetchAll();
        if(!$galleries){
            $galleries = array();
        }
        foreach($galleries as $gallery){
            //old gallery_cat is a gallery, and old subject is a cat here. no subs here, no "gallery" table there (again - the cat is the gallery etc..)
            $old_cat = $gallery['subject_id'];
            $migration_gallery_cat = self::simple_find_by_table_name(array('old_id'=>$old_cat),"migration_gallery_cat",'cat_id');
            $new_cat_id = false;
            if($migration_gallery_cat){
                $new_cat_id = $migration_gallery_cat['cat_id'];
            }
            $new_gallery = array(
                'label'=>utgt($gallery['name']),
                'site_id'=>$site_id,
                'active'=>$gallery['active'],
                'priority'=>$gallery['place']
            );
            $new_gallery_id = self::simple_create_by_table_name($new_gallery,"gallery");
            $migration_gallery = array(
                'gallery_id'=>$new_gallery_id,
                'site_id'=>$site_id,
                'unk'=>$migration_site['old_unk'],
                'old_id'=>$gallery['id']
            );
            self::simple_create_by_table_name($migration_gallery,"migration_gallery");
            if($new_cat_id){
                $gallery_cat_assign = array(
                    'gallery_id'=>$new_gallery_id,
                    'cat_id'=>$new_cat_id
                );
            }
            self::simple_create_by_table_name($gallery_cat_assign,"gallery_cat_assign");
        }




        //migrate gallery_images
        $sql = "SELECT * FROM user_gallery_images WHERE unk = :unk AND deleted = '0'";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$migration_site['old_unk']));
        $images = $req->fetchAll();
        if(!$images){
            $images = array();
        }

        foreach($images as $image){
            //old gallery_cat is a gallery, and old subject is a cat here. no subs here, no "gallery" table there (again - the cat is the gallery etc..)
            $old_gallery = $image['cat'];
            $migration_gallery = self::simple_find_by_table_name(array('old_id'=>$old_gallery),"migration_gallery",'gallery_id');
            $new_gallery_id = false;
            if($migration_gallery){
                $new_gallery_id = $migration_gallery['gallery_id'];
            }
            else{
                SystemMessages::add_err_message("קיימת תמונה ללא גלריה (#".$image['id']."), לכן לא הועתקה");
                continue;
            }

            $new_image = array(
                'label'=>utgt($image['headline']),
                'site_id'=>$site_id,
                'priority'=>$image['place'],
                'gallery_id'=>$new_gallery_id,
                'description'=>utgt($image['content']),
                'image'=>$image['img2'],
                'small_image'=>$image['img'],
            );
            
            $images_url = "http://";
            

            if($migration_site['has_ssl'] == '1'){
                $images_url = "https://";
               
            }

            $images_url .= $migration_site['domain']."/gallery/";
            $images_url = $migration_site['domain']."/gallery/";


            if($image['img2'] != ""){
                $image_url = $migration_site['domain']."/gallery/".$image['img2'];
                $new_image_url = "assets_s/".$site_id."/gallery/".$image['img2'];
                if(!file_exists($new_image_url)){                  
                    file_put_contents($new_image_url, file_get_contents($image_url));
                } 
            }
            if($image['img'] != ""){
                $small_image_url = $migration_site['domain']."/gallery/".$image['img'];
                $new_small_image_url = "assets_s/".$site_id."/gallery/".$image['img'];
                if(!file_exists($new_small_image_url)){
                    file_put_contents($new_small_image_url, file_get_contents($small_image_url));           
                }
            }

            $new_image_id = self::simple_create_by_table_name($new_image,"gallery_images");
            $migration_image = array(
                'image_id'=>$new_image_id,
                'site_id'=>$site_id,
                'unk'=>$migration_site['old_unk'],
                'old_id'=>$image['id']
            );
            self::simple_create_by_table_name($migration_image,"migration_gallery_image");

        }
    }

}
?>