<?php
  class MasterSites_content extends TableModel{

    protected static $main_table = 'sites';

    public static $fields_collection = array(
        'search_term'=>array(
            'label'=>'חיפוש',
            'type'=>'text',
            'validation'=>'required'
        ),
    );

    public static function find_in_sites($search){
      $pages_list = self::find_in_pages($search);
      return array(
        'content_pages_list'=>$pages_list['content_pages'],
        'landing_pages_list'=>$pages_list['landing_pages'],
        'product_list'=>self::find_in_products($search),
      );
    }

    public static function find_in_pages($search){
      $execute_arr = array('search'=>"%".$search."%");
      $db = Db::getInstance();
      $sql = "SELECT * FROM 
                  content_pages pages 
                  LEFT JOIN page_style style ON pages.id = style.page_id 
                  WHERE ( 
                    pages.id IN(
                              SELECT distinct page_id 
                              FROM content_blocks 
                              WHERE content LIKE :search) 
                          OR title LIKE :search 
                          OR description LIKE :search
                  );";

      
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      $page_list = $req->fetchAll();

      $content_pages_list = array();
      $landing_pages_list = array();
      foreach($page_list as $key=>$page){
        $site_info = Sites::get_by_id($page['site_id'],'title, domain, is_secure');
        if(!$site_info){
          $site_info = array(
            'is_secure'=>'0',
            'url'=>'',
            'domain'=>'',
            'title'=>'אתר לא קיים!('.$page['site_id'].')'
          );
        }
        $http_s = "http://";
        if($site_info['is_secure'] == '1'){
          $http_s = "https://";
        }
        $site_url = $http_s.$site_info['domain'];
        $site_info['url'] = $site_url;
        $page_url = $site_url."/".$page['link']."/";

        $page['site_info'] = $site_info;
        $page['url'] = $page_url;
        if($page['page_layout'] != '1'){

          $content_pages_list[$key] = $page;
        }
        else{
          $landing_pages_list[$key] = $page;
        }
      }
      return array(
        'content_pages'=>$content_pages_list,
        'landing_pages'=>$landing_pages_list
      );
    }



    public static function find_in_products($search){
        $execute_arr = array(
            'search'=>"%".$search."%");
        $db = Db::getInstance();
        $sql = "SELECT * FROM 
                    products WHERE (
                            title LIKE :search 
                            OR description LIKE :search 
                            OR description LIKE :search
                        )";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $product_list = $req->fetchAll();
        foreach($product_list as $key=>$product){
          $site_info = Sites::get_by_id($product['site_id'],'title, domain, is_secure');
          $http_s = "http://";
          if($site_info['is_secure']){
            $http_s = "https://";
          }
          $site_url = $http_s.$site_info['domain'];
          $site_info['url'] = $site_url;
          $product_url = $site_url."/products/view/?p=".$product['id'];

          $product['site_info'] = $site_info;
          $product['url'] = $product_url;
          $product_list[$key] = $product;
        }
        return $product_list;
    }

    
  }

?>