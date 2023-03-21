<?php
  class SiteCat_phone extends TableModel{

    public static function get_category_phone_info($cat_tree){
        $cat_phone_arr = array(
            'phone'=>'',
            'display_class'=>'show-normal'
        );
        if(empty($cat_tree)){
            return $cat_phone_arr;
        }
        
        foreach($cat_tree as $cat){
            if($cat['cat_phone'] != ''){
                $cat_phone_arr['phone'] = $cat['cat_phone'];
            }
        }
        $cat_display_hours_fallbeck = true;
        $cat_i_check = count($cat_tree);
        while($cat_display_hours_fallbeck){
            $cat_i_check--;
            if(!isset($cat_tree[$cat_i_check])){
                $cat_display_hours_fallbeck = false;
                return $cat_phone_arr;
            }
            $cat_id = $cat_tree[$cat_i_check]['id'];
            $cat_phone_display_hours = self::get_cat_phone_display_hours($cat_id);
            if($cat_phone_display_hours && $cat_phone_display_hours['display'] == '1' && $cat_phone_display_hours['time_groups'] != ''){
                $cat_display_hours_fallbeck = false;
                if(!self::find_now_in_time_groups($cat_phone_display_hours['time_groups'])){
                    $cat_phone_arr['display_class'] = 'hidden';
                }
            }
        } 
        return $cat_phone_arr;           
    }

    protected static function get_cat_phone_display_hours($cat_id){
        $execute_arr = array('cat_id'=>$cat_id);
        $sql = "SELECT * FROM cat_phone_display_hours WHERE cat_id = :cat_id";  
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $result = $req->fetch();
        return $result;
    }

    protected static function find_now_in_time_groups($time_groups_json){
        $weekdays_index = array(
            'Sun'=>'1',
            'Mon'=>'2',
            'Tue'=>'3',
            'Wed'=>'4',
            'Thu'=>'5',
            'Fri'=>'6',
            'Sat'=>'7'
        );

        $now_timestamp = time();
        $today = date('D',$now_timestamp);
        $today_index = $weekdays_index[$today];
        $hour = date('H',$now_timestamp);
        $minute = date('i',$now_timestamp);
        $now_arr['day'] = $today_index;
        $now_arr['hour'] = $hour;
        $now_arr['minute'] = $minute;
        $time_groups = json_decode($time_groups_json, true);
        $ok_found = false;
        foreach($time_groups as $time_group){
            if($ok_found){
                continue;
            }
            if(isset($time_group['d'][$today_index])){
                
                if((
                    $time_group['hf'] < $now_arr['hour'] || (
                        $time_group['hf'] == $now_arr['hour'] && 
                        $time_group['mf'] <= $now_arr['minute'])
                    )
                     && 
                    (
                        $time_group['ht'] > $now_arr['hour'] || (
                            $time_group['ht'] == $now_arr['hour'] && 
                            $time_group['mt']>= $now_arr['minute'])
                    )){
                        $ok_found = true;
                        break;
                }
            }
        }
        return $ok_found;
    }
}
?>