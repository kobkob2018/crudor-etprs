<?php
  class SiteMenuItems extends TableModel{


    protected static $main_table = 'menu_items';

    public static function match_page_menu_items($site_id, $menu_id, $page_id){
        $items_arr = array();
        $page_item = self::match_page_menu_item($site_id, $menu_id, $page_id);
        $root_items = false;
        $child_items = false;
        if($page_item){
            $filter_arr = array('site_id'=>$site_id,'menu_id'=>$menu_id,'parent'=>$page_item['id'],'active'=>'1');
            $root_items = self::simple_get_item_parents_tree($page_item['id']);
            $child_items = self::simple_get_list($filter_arr);
            if(!$child_items && $page_item['parent'] != '0'){
                $filter_arr['parent'] = $page_item['parent'];
                $child_items = self::simple_get_list($filter_arr);
                foreach($child_items as $item_key=>$item){
                    if($item['id'] == $page_item['id']){
                        unset($child_items[$item_key]);
                    }
                }
            }
        }
        else{
            $filter_arr = array('site_id'=>$site_id,'menu_id'=>$menu_id,'parent'=>'0', 'active'=>'1');
            $child_items = self::simple_get_list($filter_arr);
        }
        if($root_items){
            $items_arr = array_merge($items_arr,$root_items);
        }
        if($page_item){
            $items_arr[] = $page_item;
        }
        if($child_items){
            $items_arr = array_merge($items_arr,$child_items);
        }
        $item_urls = self::match_urls_to_items($items_arr);
        if($root_items){
            foreach($root_items as $item_key=>$item){
                $css_class = ' a-root-item ';
                if($page_item && $page_item['id'] == $item['id']){
                    $css_class = ' a-current-item ';
                }
                $root_items[$item_key]['css_class'] = $item['css_class'].$css_class;
                $root_items[$item_key]['final_url'] = $item_urls[$item['id']];
            }
        }
        if($page_item){
            $page_item['css_class'] = $page_item['css_class'].' a-current-item ';
            $page_item['final_url'] = $item_urls[$page_item['id']];
        }
        if($child_items){
            foreach($child_items as $item_key=>$item){
                $child_items[$item_key]['css_class'] = $item['css_class'].' a-child-item ';
                $child_items[$item_key]['final_url'] = $item_urls[$item['id']];
            }
        }
        return array(
            'root_items'=>$root_items,
            'page_item'=>$page_item,
            'child_items'=>$child_items
        );
    }

    public static function match_page_menu_item($site_id, $menu_id, $page_id){
        $page_item = false;
        if($page_id != '-1'){
            $filter_arr = array('site_id'=>$site_id,'menu_id'=>$menu_id, 'link_type'=>'1', 'page_id'=>$page_id, 'active'=>'1');
            $page_item = self::simple_find($filter_arr);
        }
        return $page_item;
    }

    public static function get_menu_items_tree($site_id, $menu_id, $page_id = false, $menu_item_id = '0'){
        
        $filter_arr = array(
            'site_id'=>$site_id,
            'menu_id'=>$menu_id,
            'parent'=>$menu_item_id
        );
        $items = self::simple_get_list($filter_arr);
        if(is_array($items)){
            
            foreach($items as $item_key=>$menu_item){
                $children = self::get_menu_items_tree($site_id, $menu_id, $page_id, $menu_item['id']);
                if($children){
                    
                    $items[$item_key]['children'] = $children;
                }
            }
        }
        $items = self::adjust_urls_to_items($items);
        return $items;
    }

    public static function adjust_urls_to_items($items_arr){
        $item_urls = self::match_urls_to_items($items_arr);
        foreach($items_arr as $item_key=>$item){
            $items_arr[$item_key]['final_url'] = $item_urls[$item['id']];

        }
        return $items_arr;
    }

    public static function match_urls_to_items($items_arr){
        $item_ids_in_arr = array();
        $items_final_urls = array();
        foreach($items_arr as $item){
            if($item['link_type'] == '0'){
                $items_final_urls[$item['id']] = $item['url'];
            }
            if($item['link_type'] == '1'){
                $item_ids_in_arr[$item['id']] = $item['page_id'];
            }
            if($item['link_type'] == '2'){
                $items_final_urls[$item['id']] = '';
            }
            
        }
        $page_links = array();
        if(!empty($item_ids_in_arr)){

            $item_ids_in_str = implode(", ",$item_ids_in_arr);
            $sql = "SELECT id, link FROM content_pages WHERE id IN ($item_ids_in_str) ";
            $db = Db::getInstance();		
            $req = $db->prepare($sql);
            $req->execute();
            $page_links_result = $req->fetchAll();
            if($page_links_result){
                foreach($page_links_result as $page_info){
                    $page_links[$page_info['id']] = $page_info['link'];
                }
            }
        }
        foreach($item_ids_in_arr as $item_id=>$page_id){
            if(isset($page_links[$page_id])){

                $page_link = $page_links[$page_id];
                $items_final_urls[$item_id] = inner_url($page_link."/");
            }
            else{
                $items_final_urls[$item_id] = "#";
            }
        }
        return $items_final_urls;
    }

    public static $menu_type_list = array(
        'top'=>'1',
        'right'=>'2',
        'hero'=>'3',
        'bottom'=>'4'
    );

}
?>