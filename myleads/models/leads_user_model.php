<?php
  class Leads_user extends Model{
    
    public static $users_cat_options = array();
    public static $users_data = array();
    public static function get_leads_user_data($user){
        $user_id = $user['id'];
        if(isset(self::$users_data[$user_id])){
            return self::$users_data[$user_id];
        }

        $db = Db::getInstance();
        $execute_arr = array('user_id'=>$user_id);
		$sql = "SELECT * FROM user_lead_settings WHERE user_id = :user_id";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        $user_lead_settings = $req->fetch();

        $lead_settings_default_values = array(
            'active'=>'0',
            'end_date'=>'',
            'lead_price'=>'0',
            'lead_price_no_tax'=>'0',
            'open_mode'=>'0',
            'auto_send'=>'0',
            'free_send'=>'0',
            'lead_credit'=>'0',
            'month_max'=>'0',
            'flex_max'=>'0',
            'rotation_priority'=>'0'
        );

		$sql = "SELECT * FROM user_lead_visability WHERE user_id = :user_id";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        $user_lead_visability = $req->fetch();

        $lead_visability_default_values = array(
            'show_in_sites'=>'0',
            'show_in_leads_report'=>'',
            'show_in_misscalls_report'=>'0',
            'send_lead_alerts'=>'0',
            'send_monthly_report'=>'0',
            'send_advanced_report'=>'0',
            'access_refunds'=>'0',
            'access_records'=>'0',
            'records_password'=>'',
        );


        $user_data = $user;


        foreach($lead_settings_default_values as $key=>$val){
            if(isset($user_lead_settings[$key])){
                $user_data[$key] = $user_lead_settings[$key];
            }
            else{
                $user_data[$key] = $val;
            }
        }

        foreach($lead_visability_default_values as $key=>$val){
            if(isset($user_lead_visability[$key])){
                $user_data[$key] = $user_lead_visability[$key];
            }
            else{
                $user_data[$key] = $val;
            }
        }

        $user_data['has_special_closed_lead_alert']	= 	'0';
        if($user_data['free_send'] == '0' && $user_data['open_mode'] == '1' && $user_data['auto_send'] == '1'){
            $user_data['has_special_closed_lead_alert']	= 	'1';
        }

        $user_data['buy_minimum']	= 	'0';
        $user_data['have_net_banners']	= false;

        self::$users_data[$user_id] = $user_data;
        
        return self::$users_data[$user_id];
    }

    public static function get_user_cat_options($user_id){
        if(isset(self::$users_cat_options[$user_id])){
            return self::$users_cat_options[$user_id];
        }

        $user_cat_arrs = array(
            'parents'=>array(),
            'flat'=>array()
        );

        $execute_arr = array('user_id'=>$user_id);
		$db = Db::getInstance();
		$sql = "SELECT bc.id,bc.label,bc.parent FROM user_cat uc LEFT JOIN biz_categories bc ON bc.id = uc.cat_id WHERE user_id = :user_id";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        $user_cats = $req->fetchAll();
        
        foreach($user_cats as $cat){

            $cat['belong_to_user'] = true;
            $cat['children'] = array();
            $user_cat_arrs['flat'][$cat['id']] = $cat;
        }
        foreach($user_cat_arrs['flat'] as $cat){
            $user_cat_arrs = self::fix_cat_parents_to_tree($cat,$user_cat_arrs);
        }
        
        $flatten_cat_options = array(
            array('id'=>'0','label'=>'כל הקטגוריות','selected'=>'','belong_to_user'=>true),
        );

        foreach($user_cat_arrs['parents'] as $parent_cat_id){
            $parent_cat = $user_cat_arrs['flat'][$parent_cat_id]; 
            
            $flatten_cat_options = self::add_cat_to_flatten_options($parent_cat, $flatten_cat_options);
        }
        

        self::$users_cat_options[$user_id] = $flatten_cat_options;
        return self::$users_cat_options[$user_id];

    }

    protected static function add_cat_to_flatten_options($parent_cat, $flatten_cat_options,$deep = 0){
        $deep++;
        $parent_cat['deep'] = $deep;
        $parent_cat['base'] = $deep;
        $parent_cat['selected'] = '';
        $flatten_cat_options[] = $parent_cat;
        foreach($parent_cat['children'] as $child_cat){
            $flatten_cat_options = self::add_cat_to_flatten_options($child_cat, $flatten_cat_options,$deep);
        }
        return $flatten_cat_options;
    }

    protected static function fix_cat_parents_to_tree($cat,$user_cat_arrs){
        if($cat['parent'] == '0'){
            if(!in_array($cat['id'],$user_cat_arrs['parents'])){
                $user_cat_arrs['parents'][] = $cat['id'];
            } 
        }
        else{
            $parent_id = $cat['parent'];
            if(isset($user_cat_arrs['flat'][$parent_id])){              
                $cat_parent = $user_cat_arrs['flat'][$parent_id];
            }
            else{
                
                $cat_parent = self::get_biz_cat_by_id($parent_id);
            }
            $cat_parent['children'][$cat['id']] = $cat;
            $user_cat_arrs['flat'][$cat_parent['id']] = $cat_parent;
            $user_cat_arrs = self::fix_cat_parents_to_tree($cat_parent,$user_cat_arrs);
            
        }
        return $user_cat_arrs;
    }

    protected static function get_biz_cat_by_id($cat_id){
        $db = Db::getInstance();
        $execute_arr = array('cat_id'=>$cat_id);
        $sql = "SELECT id, label, parent FROM biz_categories  WHERE id = :cat_id";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        $cat = $req->fetch();
        if(!$cat){
            $cat = array(
                'id'=>'-1',
                'label'=>'---',
                'parent'=>'0'
            );
        }
        $cat['belong_to_user'] = false;
        $cat['children'] = array();
        return $cat;
    }
	
  }
?>