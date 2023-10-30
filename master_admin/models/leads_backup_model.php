<?php
  class Leads_backup extends TableModel{

    protected static $main_table = 'estimate_form';

    private static $leads_bk_db = NULL;
    
    protected static function getLeadsDb() {
        if (!isset(self::$leads_bk_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$leads_bk_db = new PDO('mysql:host='.get_config('db_host').';dbname=ilbiz_leads_backup', get_config('db_user'), get_config('db_password'), $pdo_options);      
          }
        return self::$leads_bk_db;
    }

    public static function get_request_list($filter){
        $return_arr = array(
            'row_count'=>'0',
            'biz_requests'=>array()
        );
        if(!isset($filter)){
            return $return_arr;
        }
        if(!isset($filter['free']) || $filter['free'] == ''){
            return $return_arr;
        }

        $bk_db = self::getLeadsDb();
        $where_arr = self::get_where_arr($filter);
        $where_str = $where_arr['where_str'];
        $where_params = $where_arr['where_params'];
        
        //count all
        $sql = "SELECT COUNT(req.id) as row_count FROM estimate_form req WHERE $where_str"; 		
        $req = $bk_db->prepare($sql);
        $req->execute($where_params);
        $row_count_result =  $req->fetch();
        $row_count = '0';
        if($row_count_result){
            $row_count = $row_count_result['row_count'];
        }
        $row_count = intval($row_count);
        //get rows in page
        $sql = "SELECT * FROM estimate_form req WHERE $where_str ORDER BY id desc "; 		
        $sql.= " ".$where_arr['limit_str'];
        $req = $bk_db->prepare($sql);
        $req->execute($where_params);
        $biz_requests =  $req->fetchAll();

        $cat_params = array('cat_f','cat_s','cat_spec');
        $cat_labels = array();
        $city_labels = array();
        foreach($biz_requests as $key=>$biz_request){
           
            $cat_label_arr = array();

            foreach($cat_params as $cat_param){
                if($biz_request[$cat_param] != '' && $biz_request[$cat_param] != '0'){
                    $cat = $biz_request[$cat_param];
                    if(!isset($cat_labels[$cat])){
                        $cat_labels[$cat] = self::get_cat_label($cat);
                    }
                    $cat_label_arr[] = $cat_labels[$cat];
                }
            }
            $city_label = $city_id = $biz_request['city'];

            if(!isset($city_labels[$city_id])){
                $city_labels[$city_id] = self::get_city_label($city_id);
                 if($city_labels[$city_id] != ""){
                    $city_label = $city_labels[$city_id];
                 }   
            }
            $biz_request['cat_label'] = $city_label;

            $biz_request['cat_label'] = implode(", ",$cat_label_arr);
            
            $biz_requests[$key] = $biz_request;

        }
        $return_arr = array(
            'row_count'=>$row_count,
            'biz_requests'=>$biz_requests
        );
        return $return_arr;
    }

    public static function get_contacts_list($filter){
        $return_arr = array(
            'row_count'=>'0',
            'contacts'=>array()
        );
        if(!isset($filter)){
            return $return_arr;
        }
        if(!isset($filter['free']) || $filter['free'] == ''){
            return $return_arr;
        }

        $bk_db = self::getLeadsDb();
        $where_arr = self::get_where_arr($filter,'contacts');
        $where_str = $where_arr['where_str'];
        $where_params = $where_arr['where_params'];
        
        //count all
        $sql = "SELECT COUNT(req.id) as row_count FROM contacts req WHERE $where_str"; 		
        $req = $bk_db->prepare($sql);
        $req->execute($where_params);
        $row_count_result =  $req->fetch();
        $row_count = '0';
        if($row_count_result){
            $row_count = $row_count_result['row_count'];
        }
        $row_count = intval($row_count);
        //get rows in page
        $sql = "SELECT * FROM contacts req WHERE $where_str ORDER BY id desc "; 		
        $sql.= " ".$where_arr['limit_str'];
        $req = $bk_db->prepare($sql);
        $req->execute($where_params);
        $biz_requests =  $req->fetchAll();

        foreach($biz_requests as $key=>$biz_request){
            $biz_requests[$key] = $biz_request;
        }
        $return_arr = array(
            'row_count'=>$row_count,
            'contacts'=>$biz_requests
        );
        return $return_arr;
    }


    public static function get_calls_list($filter){
        $return_arr = array(
            'row_count'=>'0',
            'calls'=>array()
        );
        if(!isset($filter)){
            return $return_arr;
        }
        if(!isset($filter['free']) || $filter['free'] == ''){
            return $return_arr;
        }

        $bk_db = self::getLeadsDb();
        $where_arr = self::get_where_arr($filter,'sites_leads_stat');
        $where_str = $where_arr['where_str'];
        $where_params = $where_arr['where_params'];
        
        //count all
        $sql = "SELECT COUNT(req.id) as row_count FROM sites_leads_stat req WHERE $where_str"; 		
        $req = $bk_db->prepare($sql);
        $req->execute($where_params);
        $row_count_result =  $req->fetch();
        $row_count = '0';
        if($row_count_result){
            $row_count = $row_count_result['row_count'];
        }
        $row_count = intval($row_count);
        //get rows in page
        $sql = "SELECT * FROM sites_leads_stat req WHERE $where_str ORDER BY id desc "; 		
        $sql.= " ".$where_arr['limit_str'];
        $req = $bk_db->prepare($sql);
        $req->execute($where_params);
        $biz_requests =  $req->fetchAll();

        
        $user_names = array();
        foreach($biz_requests as $key=>$biz_request){
            $biz_request['customer_name'] = '';
            if($biz_request['unk']!=''){
                if(!isset($user_names[$biz_request['unk']])){
                    $user_names[$biz_request['unk']] = self::get_user_name_by_unk($biz_request['unk']);
                }
                $biz_request['customer_name'] = $user_names[$biz_request['unk']];
            }
            $biz_requests[$key] = $biz_request;

        }
        $return_arr = array(
            'row_count'=>$row_count,
            'calls'=>$biz_requests
        );
        return $return_arr;
    }


    protected static function get_user_name_by_unk($unk){
        $sql = "SELECT full_name, name FROM users WHERE unk  = :unk";
        $bk_db = self::getLeadsDb();
        $req = $bk_db->prepare($sql);
        $req->execute(array('unk'=>$unk));
        $user =  $req->fetch();
        if(!$user){
            return "";
        }
        return $user['full_name']."<br/>".$user['name'];
    }

    protected static function get_cat_label($cat_id){
        $sql = "SELECT cat_name FROM biz_categories WHERE id  = :cat_id";
        $bk_db = self::getLeadsDb();
        $req = $bk_db->prepare($sql);
        $req->execute(array('cat_id'=>$cat_id));
        $cat =  $req->fetch();
        if(!$cat){
            return "";
        }
        return $cat['cat_name'];
    }

    protected static function get_city_label($city_id){
        if(!is_numeric($city_id)){
            return $city_id;
        }
        $sql = "SELECT name FROM cities WHERE id  = :city_id";
        $bk_db = self::getLeadsDb();
        $req = $bk_db->prepare($sql);
        $req->execute(array('city_id'=>$city_id));
        $city =  $req->fetch();
        if(!$city){
            return self::get_area_label($city_id);
        }
        return $city['name'];
    }

    protected static function get_area_label($area_id){
        $sql = "SELECT name FROM areas WHERE id  = :area_id";
        $bk_db = self::getLeadsDb();
        $req = $bk_db->prepare($sql);
        $req->execute(array('area_id'=>$area_id));
        $area_id =  $req->fetch();
        if(!$area_id){
            return "";
        }
        return $area_id['name'];
    }

    protected static function get_where_arr($filter,$table = 'estimate_form'){
        $where_str = "1";
        $where_params = array();
        
        if($filter['free'] != ''){
            if($table == 'estimate_form'){
                $where_str .= " AND (req.phone LIKE :free OR req.email LIKE :free OR req.name LIKE :free) ";
            }
            elseif($table == 'contacts'){
                $where_str .= " AND (req.phone LIKE :free OR req.email LIKE :free OR req.full_name LIKE :free) ";
            }
            else{
                $where_str .= " AND (req.call_from LIKE :free) ";
            }
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