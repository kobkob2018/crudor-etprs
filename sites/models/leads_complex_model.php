<?php
  // to debug here, put in js console: help_debug_forms();
  
  class Leads_complex extends TableModel{
    protected static $users_arr; 


    public static function find_users_for_lead($lead_info){
        $lead_info = self::get_category_defaults($lead_info);
        $lead_info['cat_id_arr'] = self::get_tree_id_arr($lead_info['cat_tree']);
        $lead_info['city_id_arr'] = self::get_tree_id_arr($lead_info['city_tree']);

        
        $optional_user_ids = self::get_cat_user_ids($lead_info);
        
        $optional_user_ids = self::filter_inactive_users($optional_user_ids);

        $optional_user_ids = self::filter_city_users($optional_user_ids, $lead_info);

        $duplicated_user_leads = self::get_duplicated_user_leads($optional_user_ids, $lead_info);

       // user_ids
       // send_count
        $lead_sends_arr = self::get_lead_max_sends_arr($optional_user_ids, $lead_info, $duplicated_user_leads);
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
                'lead_settings'=>self::$users_arr[$user_info['id']]['lead_settings']
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
        

        $user_id_in = implode(",",$optional_user_ids);
        
        
        $sql = "SELECT * FROM user_lead_rotation WHERE id IN($user_id_in) ORDER BY order_state";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
        $result = $req->fetchAll();
        
        $check_user_ids = array();
        $users_in_rotation = array();
        $users_in_end_rotation = array();
        $user_count = 0;
        $max_sends_arr_int = intval($lead_info['max_lead_send']);
        
        foreach($result as $user){
            
            $check_user_ids[$user['id']] = '1';

            $user_lead_settings = self::$users_arr[$user['id']]['lead_settings'];
            $user_month_max = intval($user_lead_settings['month_max']);
            $user_leads_recived = intval($user['leads_recived']);

            
            if($user_count < $max_sends_arr_int || $max_sends_arr_int == 0){
                
                if($user_leads_recived < $user_month_max || $user_month_max == 0){
                    
                    if(isset($duplicated_user_leads[$user['id']])){
                        $users_in_end_rotation[] = $user['id'];
                    }
                    else{
                        $users_in_rotation[] = $user['id'];
                        $user_count++;
                    }
                }
                else{
                    
                    if($user_lead_settings['flex_max'] == '1'){
                        $users_in_end_rotation[] = $user['id'];
                    }
                }
            }
        }
        
        if($user_count < $max_sends_arr_int){
            foreach($users_in_end_rotation as $user_id){
                if($user_count < $max_sends_arr_int){
                    $users_in_rotation[] = $user_id;
                    $user_count++;
                }
            }
        }

        foreach($optional_user_ids as $check_user_id){
            if(!isset($check_user_ids[$check_user_id])){
                // this is only for bug in which rotation row was not created for the user
                //normally a row should exist
                self::fix_user_id_in_rotation_table($check_user_id);
            }
        }
        $max_sends_arr['user_ids'] = $users_in_rotation;
        $max_sends_arr['send_count'] = $user_count;
        return $max_sends_arr;
    }

    // this is only for bug in which rotation row was not created for the user
    //normally a row should exist
    public static function fix_user_id_in_rotation_table($user_id){
        $sql = "INSERT INTO user_lead_rotation(user_id) VALUES($user_id)";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
    }

    public static function filter_inactive_users($optional_user_ids){
        if(empty($optional_user_ids)){
            return $optional_user_ids;
        }
        $user_id_in = implode(",",$optional_user_ids);
        $sql = "SELECT * FROM user_lead_settings WHERE user_id IN($user_id_in)  
        AND active = '1' 
        AND (end_date > now() OR end_date = '0000-00-00' OR end_date IS NULL)";
       
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
        $result = $req->fetchAll();
        $user_ids = array();
        $users_arr = array();
        foreach($result as $user_lead_settings){
            $user_ids[] = $user_lead_settings['user_id'];
            $users_arr[$user_lead_settings['user_id']] = array(
                'lead_settings'=>$user_lead_settings
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
        $cat_id_in =  implode(",",$lead_info['cat_id_arr']);
        $city_id_in =  implode(",",$lead_info['city_id_arr']);

        $sql = "SELECT distinct user_id FROM user_city 
                WHERE user_id IN($user_id_in)
                AND city_id IN($city_id_in)";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
        $result = $req->fetchAll();
        $user_ids = array();
        foreach($result as $user){
            $user_ids[] = $user['user_id'];
        }

        $sql = "SELECT distinct user_id FROM user_cat_city 
                WHERE user_id IN($user_id_in)
                AND city_id IN($city_id_in) 
                AND cat_id IN($cat_id_in)";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
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
        $cat_id_in = implode(",",$lead_info['cat_id_arr']);
        $sql = "SELECT distinct user_id FROM user_cat WHERE cat_id IN ($cat_id_in)";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
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
        
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
    }
}
?>