<?php
  // to debug here, put in js console: help_debug_forms();
  
  class Leads_complex extends TableModel{
    protected static $users_arr; 


    public static function find_users_for_lead($lead_info){
        $lead_info = self::get_category_defaults($lead_info);
        $lead_info['cat_id_arr'] = self::get_tree_id_arr($lead_info['cat_tree']);
        $lead_info['city_id_arr'] = self::get_tree_id_arr($lead_info['city_tree']);
        foreach($lead_info['city_offsrings'] as $city){
            $lead_info['city_id_arr'][] = $city['id'];
        }
        
        $optional_user_ids = self::get_cat_user_ids($lead_info);

        if(isset($_REQUEST['prevent_db_listing'])){
            print_r_help($optional_user_ids, "get_cat_user_ids");   
        }

        $optional_user_ids = self::filter_inactive_users($optional_user_ids);

        if(isset($_REQUEST['prevent_db_listing'])){
            print_r_help($optional_user_ids, "filter_inactive_users");   
        }

        $optional_user_ids = self::filter_city_users($optional_user_ids, $lead_info);

        if(isset($_REQUEST['prevent_db_listing'])){
            print_r_help($optional_user_ids, "filter_city_users");   
        }

        $duplicated_user_leads = self::get_duplicated_user_leads($optional_user_ids, $lead_info);

        if(isset($_REQUEST['prevent_db_listing'])){
            print_r_help($duplicated_user_leads, "get_duplicated_user_leads");   
        }

       // user_ids
       // send_count
        $lead_sends_arr = self::get_lead_max_sends_arr($optional_user_ids, $lead_info, $duplicated_user_leads);

        if(isset($_REQUEST['prevent_db_listing'])){
            print_r_help($lead_sends_arr, "get_lead_max_sends_arr");   
        }

       // user_ids
       // send_count
       // users (info , lead_settings)        
        $lead_sends_arr = self::get_users_info($lead_sends_arr);

        self::update_users_rotation($lead_sends_arr);

        return $lead_sends_arr;
    }

    public static function get_category_defaults($lead_info){
        $cat_tree = $lead_info['cat_tree'];
        $defaults = array(
            'max_lead_send'=>'0'
        );
        foreach($defaults as $key=>$value){
            foreach($cat_tree as $cat){
                if(isset($cat[$key]) && $cat[$key] != '0' && $cat[$key] != ""){
                    $defaults[$key] = $cat[$key];
                } 
            }
        }
        foreach($defaults as $key=>$value){
            $lead_info[$key] = $value;
        }
        return $lead_info;
    }


    public static function get_users_info($lead_sends_arr){
        // 'user_ids',
        // 'send_count'
        $lead_sends_arr['users'] = array();
        $user_ids = $lead_sends_arr['user_ids'];
        if(empty($user_ids)){
            return $lead_sends_arr;
        }
        $user_id_in = implode(",",$lead_sends_arr['user_ids']);
        $sql = "SELECT * FROM users WHERE id IN ($user_id_in)";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
        $result = $req->fetchAll();
        $users = array();
        foreach($result as $user_info){
            $users[$user_info['id']] = array(
                'info'=>$user_info,
                'lead_settings'=>self::$users_arr[$user_info['id']]['lead_settings'],
                'lead_visability'=>self::$users_arr[$user_info['id']]['lead_visability']
            );
        }
        $lead_sends_arr['users'] = $users;
        return $lead_sends_arr;
    }

    public static function update_users_rotation($lead_sends_arr){
        
        $users = $lead_sends_arr['users'];
        if(empty($users)){
            return;
        }
        foreach($users as $user){
            $user_info = $user['info'];
            $user_lead_settings = $user['lead_settings'];
            
            
            $rotation_priority = intval($user_lead_settings['rotation_priority']);
            $rotation_add = 100 - $rotation_priority;
            $execute_arr = array('user_id'=>$user_info['id']);
            $sql = "UPDATE user_lead_rotation SET leads_recived = leads_recived + 1, order_state = order_state + $rotation_add WHERE user_id = :user_id";
            $db = Db::getInstance();		
            $req = $db->prepare($sql);
            $req->execute($execute_arr);
        }
    }


    protected static function get_users_by_rotation($optional_user_ids){
        if(empty($optional_user_ids)){
            return array();
        }
        $user_id_in = implode(",",$optional_user_ids);
        if(isset($_REQUEST['prevent_db_listing'])){
            print_help($user_id_in, "user_id_in (line 140 leads_complex_model");   
        } 
        $sql = "SELECT * FROM user_lead_rotation WHERE user_id IN($user_id_in) ORDER BY order_state";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
        $users_in_rotation = $req->fetchAll();
        return $users_in_rotation;
    }

    public static function get_lead_max_sends_arr($optional_user_ids, $lead_info, $duplicated_user_leads){
        $users_send_count = count($optional_user_ids);
        if($users_send_count > 0){
            self::reset_last_month_users($optional_user_ids);
        }
        $max_sends_arr = array(
            'user_ids'=>$optional_user_ids,
            'send_count'=>$users_send_count,
            'duplicate_user_leads'=>$duplicated_user_leads
        );
        if($lead_info['max_lead_send'] == '0' || $lead_info['max_lead_send'] == ''){
            return $max_sends_arr;
        }
        
        $users_from_rotation = self::get_users_by_rotation($optional_user_ids);

        $check_user_ids = array();
        foreach($users_from_rotation as $user_rotation){
            $check_user_ids[$user_rotation['user_id']] = '1';
        }
        $users_rotation_checked = true;
        foreach($optional_user_ids as $check_user_id){
            if(!isset($check_user_ids[$check_user_id])){
                // this is only for bug in which rotation row was not created for the user
                //normally a row should exist
                self::fix_user_id_in_rotation_table($check_user_id);
                $users_rotation_checked = false;
            }
        }
        if(!$users_rotation_checked){
            $users_from_rotation = self::get_users_by_rotation($optional_user_ids);
        }
        $users_in_rotation = array();
        $users_in_end_rotation = array();
        $user_count = 0;
        $max_sends_arr_int = intval($lead_info['max_lead_send']);
        
        foreach($users_from_rotation as $user_rotation){
            
            

            $user_lead_settings = self::$users_arr[$user_rotation['user_id']]['lead_settings'];
            $user_month_max = intval($user_lead_settings['month_max']);
            $user_leads_recived = intval($user_rotation['leads_recived']);

            
            if($user_count < $max_sends_arr_int || $max_sends_arr_int == 0){
                
                if($user_leads_recived < $user_month_max || $user_month_max == 0){
                    
                    if(isset($duplicated_user_leads[$user_rotation['user_id']])){
                        $users_in_end_rotation[] = $user_rotation['user_id'];
                    }
                    else{
                        $users_in_rotation[] = $user_rotation['user_id'];

                        $user_count++;
                        if(isset($_REQUEST['prevent_db_listing'])){
                            print_r_help($users_in_rotation, "now user_count:".$user_count);   
                        } 
                    }
                }
                else{
                    
                    if($user_lead_settings['flex_max'] == '1'){
                        $users_in_end_rotation[] = $user_rotation['user_id'];
                    }
                }
            }
        }
        
        if($user_count < $max_sends_arr_int){
            foreach($users_in_end_rotation as $user_id){
                if($user_count < $max_sends_arr_int){
                    $users_in_rotation[] = $user_id;
                    $user_count++;
                    if(isset($_REQUEST['prevent_db_listing'])){
                        print_r_help($users_in_rotation, "END ROTAION-- user_count:".$user_count);   
                    } 
                }
            }
        }

        $max_sends_arr['user_ids'] = $users_in_rotation;
        $max_sends_arr['send_count'] = $user_count;
        return $max_sends_arr;
    }

    // this is only for bug in which rotation row was not created for the user
    //normally a row should exist
    public static function fix_user_id_in_rotation_table($user_id){
        $filter_arr = array('user_id'=>$user_id);
        $sql = "SELECT id FROM  user_lead_rotation WHERE user_id = :user_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($filter_arr);
        $result_row = $req->fetch();
        if($result_row){
            return $result_row['id'];
        }
        $sql = "INSERT INTO user_lead_rotation(user_id) VALUES(:user_id)";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($filter_arr);
        return $db->lastInsertId();
    }

    public static function filter_inactive_users($optional_user_ids){
        if(empty($optional_user_ids)){
            return $optional_user_ids;
        }
        $user_id_in = implode(",",$optional_user_ids);
        $sql = "SELECT * FROM user_lead_settings WHERE user_id IN($user_id_in)  
        AND active = '1' 
        AND auto_send = '1' 
        AND (end_date > now() OR end_date = 0000-00-00 OR end_date IS NULL)";
       
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
        $result = $req->fetchAll();
        $user_ids = array();
        $users_arr = array();
        foreach($result as $user_lead_settings){
            
            $sql = "SELECT * FROM user_lead_visability WHERE user_id = :user_id";
            $req = $db->prepare($sql);
            $req->execute(array('user_id'=>$user_lead_settings['user_id']));
            $user_lead_visability = $req->fetch();

            $user_ids[] = $user_lead_settings['user_id'];
            $users_arr[$user_lead_settings['user_id']] = array(
                'lead_settings'=>$user_lead_settings,
                'lead_visability'=>$user_lead_visability
            );
        }
        self::$users_arr = $users_arr;
        return $user_ids;
    }

    public static function get_user_send_times($user_id){
        $execute_arr = array('user_id'=>$user_id);
        $sql = "SELECT * FROM user_lead_send_times WHERE user_id = :user_id  
        AND display = '1' ";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $result = $req->fetch();
        if(!$result){
            return "";
        }
        return $result['time_groups'];
    }

    public static function filter_city_users($optional_user_ids, $lead_info){
        if(empty($optional_user_ids)){
            return $optional_user_ids;
        }
        $user_id_in = implode(",",$optional_user_ids);

        $sql = "SELECT distinct user_id FROM user_city 
                WHERE user_id IN($user_id_in)
                AND city_id = :city_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute(array('city_id'=>$lead_info['city_id']));
        $result = $req->fetchAll();
        $user_ids = array();
        foreach($result as $user){
            $user_ids[] = $user['user_id'];
        }

        $sql = "SELECT distinct user_id FROM user_cat_city 
                WHERE user_id IN($user_id_in)
                AND city_id = :city_id  
                AND cat_id = :cat_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute(array('city_id'=>$lead_info['city_id'], 'cat_id'=>$lead_info['cat_id']));
        $result = $req->fetchAll();
        foreach($result as $user){
            if(!in_array($user['user_id'], $user_ids)){
                $user_ids[] = $user['user_id'];
            }
        }       
        return $user_ids;
    }


    public static function get_tree_id_arr($tree){
        $tree_id_arr = array();
        foreach($tree as $item){
            $tree_id_arr[] = $item['id'];
        }
        return $tree_id_arr;
    }

    public static function get_cat_user_ids($lead_info){
        $sql = "SELECT distinct user_id FROM user_cat WHERE cat_id = :cat_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute(array('cat_id'=>$lead_info['cat_id']));
        $result = $req->fetchAll();
        $user_ids = array();
        foreach($result as $user){
            $user_ids[] = $user['user_id'];
        }
        return $user_ids;
    }

    public static function get_duplicated_user_leads($optional_user_ids,$lead_info){
        if(!isset($lead_info['phone']) || $lead_info['phone'] == ''){
            return array();
        }
        $users_duplicate_leads = array();
        $phone = $lead_info['phone'];
        $execute_arr = array('phone'=>$phone);
        
        $sql = "SELECT id, user_id, duplicate_id FROM user_leads              
                WHERE (date_in > DATE_FORMAT( NOW( ) ,  '%Y-%m-01' ) AND phone = :phone)";
        
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $leads = $req->fetchAll();
        foreach($leads as $lead){
            if(in_array($lead['user_id'],$optional_user_ids)){
                $duplicate_lead_id = $lead['id'];
                if($lead['duplicate_id'] != '0' && $lead['duplicate_id'] != ''){
                    $duplicate_lead_id = $lead['duplicate_id'];
                }
                $users_duplicate_leads[$lead['user_id']] = $duplicate_lead_id;
            }
        }  
        return $users_duplicate_leads;    
    }

    public static function reset_last_month_users($optional_user_ids){
        $user_id_in = implode(",",$optional_user_ids);
        $sql = "UPDATE user_lead_rotation 
                SET last_update = NOW(),
                leads_recived = '0',
                order_state = '0'               
                WHERE (last_update < DATE_FORMAT( NOW( ) ,  '%Y-%m-01' ) AND user_id IN($user_id_in))";
        
        if(isset($_REQUEST['prevent_db_listing'])){
            print_help($sql, "preventing reset_last_month_users ");
            return;
        }

        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
    }

    public static function get_request_info_with_users($request_id){
        $execute_arr = array("request_id"=>$request_id);
        $sql = "SELECT * FROM biz_requests WHERE id = :request_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $biz_request = $req->fetch();
        if(!$biz_request){
            return false;
        }

        $return_array = array(
            'request'=>$biz_request,
            'user_ids'=>false,
            'supplier_cubes'=>false
        );

        $sql = "SELECT user_id FROM user_leads WHERE request_id = :request_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $user_leads = $req->fetchAll();

        if(!$user_leads){
            return $return_array;
        }

        $user_ids = array();

        foreach($user_leads as $user_lead){
            $user_ids[] = $user_lead['user_id'];
        }
        $return_array['user_ids'] = $user_ids;
        $user_id_in_str = implode(",",$user_ids);
        
        $sql = "SELECT * FROM supplier_cubes WHERE status != '0' AND user_id IN($user_id_in_str)"; 
        
        $req = $db->prepare($sql);
        $req->execute();
        $cubes = $req->fetchAll(); 
        $return_array['supplier_cubes'] = $cubes;
        
        return $return_array;
    }

    public static function update_page_convertions($page_id, $column = 'convertions'){
        if($page_id == '' || $page_id == '-1' || $page_id == '0'){
            return;
        }
        $sql = "UPDATE content_pages SET $column = $column + 1 WHERE id = :page_id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute(array('page_id'=>$page_id));
    }
}
?>