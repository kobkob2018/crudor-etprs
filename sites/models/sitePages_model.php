<?php
  class SitePages extends TableModel{

    protected static $main_table = 'content_pages';

    protected static $current_page = false;
    protected static $pages_by_link = array();
    protected static $pages_by_id = array();

    public static function get_current_page(){
        if(self::$current_page){
            return self::$current_page;
        }
        if(!isset($_REQUEST['page'])){
            $site = Sites::get_current_site();
            if($site['home_page'] == '0'){
                return false;
            }
            self::$current_page = self::get_by_id($site['home_page']);
        }
        else{
            self::$current_page = self::get_by_link($_REQUEST['page']);
        }
        return self::$current_page; 
    }

    public static function get_by_id($page_id, $select_params = "*"){
        $current_site = Sites::get_current_site();
        if(!$current_site){
            return false;
        }
        if(!isset(self::$pages_by_id[$page_id])){
            self::$pages_by_id[$page_id] = self::simple_find(array('id'=>$page_id,'site_id'=>$current_site['id']));
        }
        return self::$pages_by_id[$page_id];
    }

    public static function get_by_link($link){
        $current_site = Sites::get_current_site();
        if(!$current_site){
            return false;
        }
        if(!isset(self::$pages_by_link[$link])){
            self::$pages_by_link[$link] = self::simple_find(array('link'=>$link,'site_id'=>$current_site['id']));
        }
        return self::$pages_by_link[$link];
    }

    public static $assets_mapping = array(
        'right_banner'=>'pages/banners'
    );

    public static function get_home_page_list(){
        $current_site = Sites::get_current_site();
        $execute_arr = array('site_id'=>$current_site['id']);
        $db = Db::getInstance();
        $sql = "SELECT * FROM content_pages WHERE site_id = :site_id AND active = '1' AND visible = '1' ORDER BY priority desc LIMIT 10";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $page_list = $req->fetchAll();
        return $page_list;
    }
  }
?>