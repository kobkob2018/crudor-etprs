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
        if(self::$current_page){
            self::update_page_views(self::$current_page['id']);
        }
        return self::$current_page; 
    }

    protected static function update_page_views($page_id){
        $db = Db::getInstance();
        $sql = "UPDATE content_pages SET views = views + 1 WHERE id = :page_id";	
        $req = $db->prepare($sql);
        $req->execute(array('page_id'=>$page_id));
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
            $filter_arr = array('link'=>$link,'site_id'=>$current_site['id'],'status'=>'1');
            if(isset($_REQUEST['demo_view'])){
                unset($filter_arr['status']);
            }
            self::$pages_by_link[$link] = self::simple_find($filter_arr);
        }
        return self::$pages_by_link[$link];
    }

    public static $assets_mapping = array(
        'right_banner'=>'pages/banners'
    );

    public static function get_home_page_list($tag = false, $paging = false){

        $current_site = Sites::get_current_site();
        $execute_arr = array('site_id'=>$current_site['id']);
        $db = Db::getInstance();
        $tag_sql = "";
        if($tag){
            $tag_sql = " AND tags LIKE :tag ";
            $execute_arr['tag'] = "%".$tag."%";
        }

        $sql = "SELECT count(id) as item_count FROM content_pages WHERE site_id = :site_id AND active = '1' AND visible = '1' $tag_sql";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $count_result = $req->fetch();
        $item_count = $count_result['item_count'];

        $limit = '10';
        $paging_page = '0';
        $paging_pages = '1';
        $paging_current = '1';
        if($paging){
            $limit = $paging['limit'];
            $paging_page = $paging['page']*$limit;
            $paging_pages = ceil($item_count/$limit);
            $paging_current = $paging['page'];
        }

        $sql = "SELECT * FROM content_pages WHERE site_id = :site_id AND active = '1' AND visible = '1' $tag_sql ORDER BY priority desc LIMIT $paging_page, $limit";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $page_list = $req->fetchAll();
        return array(
            'list'=>$page_list,
            'count'=>$item_count,
            'pages'=>$paging_pages,
            'current'=>$paging_current
        );
        //return $page_list;
    }

    public static function search_by_str($search){
        $current_site = Sites::get_current_site();
        $execute_arr = array('site_id'=>$current_site['id'], 'search'=>"%".$search."%");
        $db = Db::getInstance();
        $sql = "SELECT * FROM 
                    content_pages WHERE ( 
                            id IN(
                                SELECT distinct page_id 
                                FROM content_blocks 
                                WHERE site_id = :site_id 
                                AND content LIKE :search) 
                            OR title LIKE :search 
                            OR description LIKE :search
                        ) 
                        AND site_id = :site_id 
                        AND active = '1' 
                        AND visible = '1'";	



        
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $page_list = $req->fetchAll();
        return $page_list;
    }

  }
?>