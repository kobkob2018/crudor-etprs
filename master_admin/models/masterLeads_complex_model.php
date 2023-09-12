<?php
  // to debug here, put in js console: help_debug_forms();
  
  class MasterLeads_complex extends TableModel{
    protected static $users_arr; 


    public static function find_users_for_lead($lead_info){
        
        $lead_info['cat_tree'] = Biz_categories::simple_get_item_parents_tree($lead_info['cat_id'],"id,parent,label");
        $lead_info['city_tree'] = Cities::simple_get_item_parents_tree($lead_info['city_id'],"id,parent,label");

        $lead_info['cat_id_arr'] = self::get_tree_id_arr($lead_info['cat_tree']);
        $lead_info['city_id_arr'] = self::get_tree_id_arr($lead_info['city_tree']);

        $optional_users_data = array();

        $optional_user_ids = self::get_cat_tree_user_ids($lead_info); //including all cat_tree users
        
        $cat_user_ids = self::get_cat_user_ids($lead_info); //only specific cat users

        foreach($optional_user_ids as $user_id){
            
            $user_info = self::get_user_info($user_id);

            $user_lead = self::get_user_lead($user_id,$lead_info['id']);

            $optional_user = array(
                'id'=>$user_id,
                'fit_city'=>false,
                'fit_cat'=>false,
                'is_active'=>false,
                'lead_sent'=>false,
                'final_fit'=>false,
                'lead_info'=>$user_lead,
                'info'=>$user_info
            );
            if($user_lead){
                $optional_user['lead_sent'] = true;
            }

            if(in_array($user_id,$cat_user_ids)){
                $optional_user['fit_cat'] = true;
            }

            $optional_users_data[$user_id] = $optional_user;
        }

        $active_users = self::filter_inactive_users($optional_user_ids);

        foreach($active_users as $user_id){
            $optional_users_data[$user_id]['is_active'] = true;
        }


        $city_user_ids = self::filter_city_users($optional_user_ids, $lead_info);

        foreach($city_user_ids as $user_id){
            $optional_users_data[$user_id]['fit_city'] = true;
        }   


        foreach($optional_users_data as $user_id=>$user){
            $user_info = $user['info'];


            if($user_info['lead_settings']['auto_send'] == '1' && $user['fit_city'] && $user['is_active']){
                $user['final_fit'] = true;
            }
            $user['monthly_sent_leads'] = self::get_user_monthly_sent_leads($user_id);
            $optional_users_data[$user_id] = $user;
        }
        return $optional_users_data;
    }

    public static function get_user_lead($user_id,$request_id){
        $db = Db::getInstance();
        $execute_arr = array('user_id'=>$user_id,'request_id'=>$request_id);
        $sql = "SELECT * FROM user_leads WHERE user_id = :user_id AND request_id = :request_id";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        return $req->fetch();
    }

    public static function get_user_monthly_sent_leads($user_id){
        $db = Db::getInstance();
        $execute_arr = array('user_id'=>$user_id);
        $sql = "SELECT count(id) as lead_count FROM user_leads WHERE user_id = :user_id AND billed = '1' AND date_in > DATE_FORMAT(NOW() ,'%Y-%m-01')";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $result = $req->fetch();
        if(!$result){
            return '0';
        }
        return $result['lead_count'];
    }

    public static function get_user_info($user_id){
        $user_info = array();
        $db = Db::getInstance();
        $execute_arr = array('user_id'=>$user_id);
        $sql = "SELECT * FROM users WHERE id = :user_id";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $user_info['user'] = $req->fetch();

        $sql = "SELECT * FROM user_lead_settings WHERE user_id = :user_id";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $user_info['lead_settings'] = $req->fetch();

        $sql = "SELECT * FROM user_lead_rotation WHERE user_id = :user_id";	
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $user_info['lead_rotation'] = $req->fetch();
        return $user_info;
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

    public static function filter_inactive_users($optional_user_ids){
        if(empty($optional_user_ids)){
            return $optional_user_ids;
        }
        $user_id_in = implode(",",$optional_user_ids);
        $sql = "SELECT * FROM user_lead_settings WHERE user_id IN($user_id_in)  
        AND active = '1' 
        AND (end_date > now() OR end_date = 0000-00-00 OR end_date IS NULL)";
       
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute();
        $result = $req->fetchAll();
        $user_ids = array();
        foreach($result as $user_lead_settings){
            $user_ids[] = $user_lead_settings['user_id'];
        }
        
        return $user_ids;
    }

    public static function filter_city_users($optional_user_ids, $lead_info){
        
        //if($lead_info['city_id'] == '' || $lead_info['city_id'] == '0'){
        //    return $optional_user_ids;
        //}
        if(empty($optional_user_ids)){
            return $optional_user_ids;
        }
        $user_id_in = implode(",",$optional_user_ids);

        $sql = "SELECT distinct user_id FROM user_city 
                WHERE user_id IN($user_id_in)
                AND city_id  = :city_id";
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
        $req->execute(array('city_id'=>$lead_info['city_id'],'cat_id'=>$lead_info['cat_id']));
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




    public static function get_cat_tree_user_ids($lead_info){
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
}
?>