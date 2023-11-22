<?php
  class MasterBiz_requests extends TableModel{

    protected static $main_table = 'biz_requests';


    public static $fields_collection = array(

        'status'=>array(
            'label'=>'סטטוס',
            'type'=>'select',
            'default'=>'0',
            'options'=>array(
                array('value'=>'0', 'title'=>'הצעות חדשות'),
                array('value'=>'1', 'title'=>'הצעות בטיפול'),
                array('value'=>'2', 'title'=>'הצעות שמחכות'),
                array('value'=>'3', 'title'=>'הצעות שנשלחו'),
                array('value'=>'4', 'title'=>'הצעות לא רלוונטיות'),
                array('value'=>'5', 'title'=>'הצעות מחוקות')
            )
        ),

        'full_name'=>array(
            'label'=>'שם מלא',
            'type'=>'text',
            'validation'=>'required'
        ),

        'phone'=>array(
            'label'=>'טלפון',
            'type'=>'text',
            'validation'=>'required'
        ),

        'email'=>array(
            'label'=>'אימייל',
            'type'=>'text',
            'validation'=>''
        ),

        'note'=>array(
            'label'=>'הערות',
            'type'=>'textbox',
            'css_class'=>'small-text left-text',
            'validation'=>''
        ),
    );

    public static function get_request($request_id){
        $biz_request = self::get_by_id($request_id);
        if(!$biz_request){
            return false;
        }
        $city_name = "";
        if($biz_request['city_id'] != ""){
            $city = Cities::get_by_id($biz_request['city_id'],'label');
            if($city){
                $city_name = $city['label'];
            }
        }
        $biz_request['city_name'] = $city_name;
        $biz_request['cat_tree'] = Biz_categories::get_item_parents_tree($biz_request['cat_id'],'id,parent,label');
        $biz_request['banner_name'] = self::get_banner_name($biz_request['banner_id']);
        return $biz_request;
    }

    public static function get_request_list($filter){
        $db = Db::getInstance();
        $where_arr = self::get_where_arr($filter);
        $where_str = $where_arr['where_str'];
        $where_params = $where_arr['where_params'];
        
        //count all
        $sql = "SELECT COUNT(req.id) as row_count FROM biz_requests req WHERE $where_str"; 		
        $req = $db->prepare($sql);
        $req->execute($where_params);
        $row_count_result =  $req->fetch();
        $row_count = '0';
        if($row_count_result){
            $row_count = $row_count_result['row_count'];
        }
        $row_count = intval($row_count);
        //get rows in page
        $sql = "SELECT req.*, city.label as city_name FROM biz_requests req LEFT JOIN cities city ON req.city_id = city.id WHERE $where_str ORDER BY id desc "; 		
        $sql.= " ".$where_arr['limit_str'];
        $req = $db->prepare($sql);
        $req->execute($where_params);
        $biz_requests =  $req->fetchAll();
        $banner_names = array();
        foreach($biz_requests as $key=>$biz_request){
            $biz_request['cat_tree'] = Biz_categories::get_item_parents_tree($biz_request['cat_id'], 'parent, label');
            $biz_request['banner_name'] = "";
            
            if($biz_request['banner_id'] != ""){
                if(!isset($banner_names[$biz_request['banner_id']])){
                    $sql = "SELECT label FROM net_banners WHERE id = :banner_id";
                    $ex_arr = array('banner_id'=>$biz_request['banner_id']);
                    $req = $db->prepare($sql);
                    $req->execute($ex_arr);
                    $banner_result = $req->fetch();
                    if($banner_result){
                        $banner_names[$biz_request['banner_id']] = $banner_result['label'];
                    }
                    else{
                        $banner_names[$biz_request['banner_id']] = "";
                    }
                }
                $biz_request['banner_name'] = $banner_names[$biz_request['banner_id']];
            }
            
            $biz_requests[$key] = $biz_request;

        }
        $return_arr = array(
            'row_count'=>$row_count,
            'biz_requests'=>$biz_requests
        );
        return $return_arr;
    }

    protected static function get_banner_name($banner_id = ""){
        $banner_name = "";
        if($banner_id != "" && $banner_id != '0'){
            $db = Db::getInstance();
            $sql = "SELECT label FROM net_banners WHERE id = :banner_id";
            $ex_arr = array('banner_id'=>$banner_id);
            $req = $db->prepare($sql);
            $req->execute($ex_arr);
            $banner_result = $req->fetch();
            if($banner_result){
                $banner_name = $banner_result['label'];
            }
        }
        return $banner_name;
    }

    public static function add_1_reciver($request_id){
        $db = Db::getInstance();
        $sql = "UPDATE biz_requests SET recivers = recivers+1 WHERE id = :request_id"; 		
        $req = $db->prepare($sql);
        $req->execute(array('request_id'=>$request_id));
    }

    public static function get_referrer_options($filter){
        $db = Db::getInstance();
        $where_arr = self::get_where_arr($filter);
        $where_str = $where_arr['where_str'];
        $where_params = $where_arr['where_params'];
        $sql = "SELECT distinct site_ref FROM biz_requests req WHERE $where_str"; 		
        $req = $db->prepare($sql);
        $req->execute($where_params);
        $result = $req->fetchAll();
        
        $options = array();
        if(!$result){
            return $options;
        }
        foreach($result as $ref){
            if($ref['site_ref']!= ''){
                $options[] = $ref['site_ref'];
            }
        }
        return $options;
    }

    protected static function get_where_arr($filter){
        $where_str = "1";
        $where_params = array();
        if($filter['status'] != 'all'){
            $where_str .= " AND req.status = :status ";
            $where_params['status'] = $filter['status'];
        }
        if($filter['date_s'] != ''){
            $where_str .= " AND req.date_in >= :date_s ";
            $where_params['date_s'] = $filter['date_s'];
        }

        if($filter['referrer'] != ''){
            $where_str .= " AND req.referrer LIKE :referrer";
            $where_params['referrer'] = "%".$filter['referrer']."%";
        }

        if($filter['ip'] != ''){
            $where_str .= " AND req.ip  = :ip";
            $where_params['ip'] = $filter['ip'];
        }

        if($filter['free'] != ''){
            $where_str .= " AND (req.phone LIKE :free OR req.email LIKE :free OR req.full_name LIKE :free) ";
            $where_params['free'] = "%".$filter['free']."%";
        }
        if($filter['filter_campaign_types'] == '1'){
            $campaign_type_arr = array('-5'); // -5 is just fictive
            foreach($filter['campaign_types'] as $type_id=>$checked){
                if(is_numeric($type_id)){
                    $campaign_type_arr[] = $type_id;
                }
            }
            $types_in = implode(",",$campaign_type_arr);
            $where_str .= " AND req.campaign_type IN ($types_in) ";
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