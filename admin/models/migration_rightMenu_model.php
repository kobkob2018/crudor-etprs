<?php
  class Migration_rightMenu extends TableModel{

    private static $ilbiz_db = NULL;

    protected static $main_table = 'migration_rightMenu';

    protected static function getIlbizDb() {
        if (!isset(self::$ilbiz_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$ilbiz_db = new PDO('mysql:host='.get_config('ilbiz_db_host').'; port='.get_config('ilbiz_db_port').'; dbname='.get_config('ilbiz_db_db').'', get_config('ilbiz_db_user'), get_config('ilbiz_db_pass'), $pdo_options);      
          }
        return self::$ilbiz_db;
    }

    public static function check_if_migration_exist($filter){
        $db = DB::getInstance();

        $sql = "SELECT site_id FROM migration_rightMenu WHERE site_id = :site_id LIMIT 1";
        $req = $db->prepare($sql);
        $req->execute($filter);
        $result = $req->fetch();
        if($result){
            return true;
        }
        
        return false;
    }

    public static function delete_older($site_id){
        $db = DB::getInstance();

        $migration_rightMenus = self::simple_get_list_by_table_name(array('site_id'=>$site_id),'migration_rightMenu');

		if(!$migration_rightMenus){
           return;
        }
        foreach($migration_rightMenus as $migration_rightMenu){
            self::simple_delete_by_table_name($migration_rightMenu['id'],'migration_rightMenu');
            self::simple_delete_by_table_name($migration_rightMenu['item_id'],'menu_items');
        }
    }

    public static function do_migrate($site_id,$migration_site){
        return self::do_migrate_recorsive($site_id,$migration_site);
    }

    public static function do_migrate_recorsive($site_id,$migration_site,$old_father = '0',$new_parent = '0'){
        $ilbiz_db = self::getIlbizDb();

        $sql = "SELECT * FROM users_links_menu_settings WHERE unk = :unk AND father = :old_father AND deleted = '0'";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$migration_site['old_unk'], 'old_father'=>$old_father));
        $rightMenus = $req->fetchAll();
        if(!$rightMenus){
            $rightMenus = array();
        }
        foreach($rightMenus as $rightMenu){ 
            $new_rightMenu_item = array(
                'site_id'=>$site_id,
                'menu_id'=>'2',
                'parent'=>$new_parent,
                'label'=>utgt($rightMenu['link_name']),
                'priority'=>$rightMenu['place'],
                'target'=>$rightMenu['open_target'] == '_self' ? '0' : '1',
                'link_type'=>'0',
                'url'=>utgt($rightMenu['link_url']),
            );

            $check_url = utgt($rightMenu['link_url']);

            $check_url = str_replace("http://".$migration_site['old_domain']."/", "", $check_url);

            $check_url = str_replace("https://".$migration_site['old_domain']."/", "", $check_url);

            $check_url_arr = explode("/",$check_url);
            
            if(isset($check_url_arr[0]) && $check_url_arr[0] != ''){
                $page_link = $check_url_arr[0];
                $content_page = self::simple_find_by_table_name(array('site_id'=>$site_id,'link'=>$page_link),'content_pages','id');

                if($content_page){
                    if(isset($content_page[0])){
                        $new_rightMenu_item['url'] = '';
                        $new_rightMenu_item['link_type'] = '1';
                        $new_rightMenu_item['page_id'] = $content_page[0]['id'];
                    }
                }

            }

            $new_rightMenu_id = self::simple_create_by_table_name($new_rightMenu_item,"menu_items");
            $migration_rightMenu = array(
                'item_id'=>$new_rightMenu_id,
                'site_id'=>$site_id,
                'unk'=>$migration_site['old_unk'],
                'old_id'=>$rightMenu['id']
            );
            self::simple_create_by_table_name($migration_rightMenu,"migration_rightMenu");
            
            self::do_migrate_recorsive($site_id,$migration_site,$rightMenu['id'],$new_rightMenu_id);

        }
    }



}
?>