<?php
  class MasterBiz_requests_spam extends TableModel{

    protected static $main_table = 'biz_requests_spam';

    public static function get_request($request_id){
        $biz_request_row = self::get_by_id($request_id);
        
        if(!$biz_request_row){
            return false;
        }
        $biz_request = json_decode($biz_request_row['lead_info'],true);
        $city_name = "";
        if($biz_request['city_id'] != ""){
            $city = Cities::get_by_id($biz_request['city_id'],'label');
            if($city){
                $city_name = $city['label'];
            }
        }
        $biz_request['city_name'] = $city_name;
        $biz_request['cat_tree'] = Biz_categories::get_item_parents_tree($biz_request['cat_id'],'id,parent,label');
        return $biz_request;
    }

    public static function get_request_list($filter){
        $db = Db::getInstance();
        $where_arr = self::get_where_arr($filter);
        $where_str = $where_arr['where_str'];
        $where_params = $where_arr['where_params'];
        
        //count all
        $sql = "SELECT COUNT(req.id) as row_count FROM biz_requests_spam req WHERE $where_str"; 		
        $req = $db->prepare($sql);
        $req->execute($where_params);
        $row_count_result =  $req->fetch();
        $row_count = '0';
        if($row_count_result){
            $row_count = $row_count_result['row_count'];
        }
        $row_count = intval($row_count);
        //get rows in page
        $sql = "SELECT req.* FROM biz_requests_spam req WHERE $where_str ORDER BY id desc "; 		
        $sql.= " ".$where_arr['limit_str'];
        $req = $db->prepare($sql);
        $req->execute($where_params);
        $biz_requests =  $req->fetchAll();
        $list_params = array(
            "full_name",
            "phone",
            "email",
            "note",
            "ip",
            "city_id",
            "cat_id",
            "extra_info",
        );
        foreach($biz_requests as $key=>$biz_request_row){
            $biz_request = $biz_request_row;
            $lead_info = array();
            if($biz_request_row['lead_info'] != ""){
                $lead_info = json_decode($biz_request_row['lead_info'],true);
            }

            foreach($lead_info as $l_key=>$val){
                $biz_request[$l_key] = $val;
            }
            foreach($list_params as $p_key){
               // print_help($key);
                if(!isset($biz_request[$p_key])){
                   $biz_request[$p_key] = "0";
                }
            }
            $biz_request['cat_tree'] = Biz_categories::get_item_parents_tree($biz_request['cat_id'], 'parent, label');
        
            
            $biz_requests[$key] = $biz_request;

        }
        
        $return_arr = array(
            'row_count'=>$row_count,
            'biz_requests'=>$biz_requests
        );
        return $return_arr;
    }

    protected static function get_where_arr($filter){
        $where_str = "1";
        $where_params = array();

        if($filter['date_s'] != ''){
            $where_str .= " AND req.date_in >= :date_s ";
            $where_params['date_s'] = $filter['date_s'];
        }

        if($filter['free'] != ''){
            $where_str .= " AND (req.lead_info LIKE :free) ";
            $where_params['free'] = "%".$filter['free']."%";
        }

        $page = intval($filter['page']);
        $page = $page - 1;
        if($page<0){
            $page_limit = 0;
        }
        $page_limit = intval($filter['page_limit']);
        $limit_count = $page*$page_limit;
        $limit_str = " LIMIT $limit_count, $page_limit ";

        return array(
            'where_str'=>$where_str,
            'where_params'=>$where_params,
            'limit_str'=>$limit_str,
        );
    }
}
?>