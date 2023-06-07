<?php
  class Misscalls_comments extends TableModel{

    protected static $main_table = 'misscalls_comments';

    public static function get_item_cat_list($item_id){
        $filter_arr = array("banner_id"=>$item_id);
        return self::simple_get_list($filter_arr);
    }

    public static function get_comment_list(){
        $db = Db::getInstance();
        $filter_str = array(
            'user_name'=>''
        );
        $date_from_str = date("d-m-Y", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 day" ) );
        if(isset($_GET['date_from'])){
            $date_from_str = trim($_GET['date_from']);
        }
        $date_from_arr = explode("-",$date_from_str);
        $date_from_sql = $date_from_arr[2]."-".$date_from_arr[1]."-".$date_from_arr[0];
        $date_to_str = date("d-m-Y");
        if(isset($_GET['date_to'])){
            $date_to_str = trim($_GET['date_to']);
        }
    
        $filter_str['date_to'] = $date_to_str;
        $filter_str['date_from'] = $date_from_str;

        $date_to_arr = explode("-",$date_to_str);
        $date_to_sql_1 = $date_to_arr[2]."-".$date_to_arr[1]."-".$date_to_arr[0];
        $date_to_sql = date('Y-m-d', strtotime("+1 day", strtotime($date_to_sql_1)));
        
        
        if(isset($_GET['user_name']) && $_GET['user_name'] != ""){
            $filter_str['user_name'] = $_GET['user_name'];
            $user_select_sql = ' AND user.full_name LIKE ("%'.$_GET['user_name'].'%") ';
        }
        else{
            $user_select_sql = " AND ulv.show_in_misscalls_report = '1' ";
        }
        $users_arr = array();
        $user_id_in_sql = "'-1'";
        $users_sql = "SELECT user.full_name,user.id as user_id FROM  user_lead_visability ulv LEFT JOIN users user ON user.id = ulv.user_id WHERE 1 $user_select_sql";
        
        $req = $db->prepare($users_sql);
        $req->execute();   
        
        $users_res = $req->fetchAll();
        if($users_res){
            foreach($users_res as $user_data){
                $user_id = $user_data['user_id'];
                $users_arr[$user_id] = $user_data;
                $user_id_in_sql .= ",'$user_id'"; 
            }
        }

        $leads_sql = "SELECT * FROM user_leads WHERE user_id IN($user_id_in_sql) AND date_in >= '$date_from_sql' AND date_in <= '$date_to_sql' AND resource = 'phone'";
        
        $req = $db->prepare($leads_sql);
        $req->execute();   
        
        $leads_res = $req->fetchAll();

        if(!$leads_res){
            $leads_res = array();
        }

        $owners_list = array();
        $answerd_count = 0;
        $noanswer_count = 0;
        $doubled_answerd_count = 0;
        $doubled_noanswer_count = 0;	
        $unk_check = 0;
        foreach($leads_res as $lead_data){
            
            $user_id = $lead_data['user_id'];
            if(!isset($users_arr[$user_id]['checked_phones'])){
                $users_arr[$user_id]['checked_phones'] = array();
                
            }
            $check_phone = $lead_data['phone'];
            if(isset($users_arr[$user_id]['checked_phones'][$check_phone])){
                continue;
            }
            $date_in = $lead_data['date_in'];
            $lead_date_from = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $date_in ) ) . "-3 day" ) );
            $lead_date_to = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $date_in ) ) . "+3 day" ) );
            if(!isset($users_arr[$user_id]['leads'])){
                $users_arr[$user_id]['leads'] = array();	
            }		
            $check_lead_sql = " SELECT user_id FROM user_leads WHERE phone = '$check_phone' AND date_in > '$lead_date_from' AND date_in < '$lead_date_to' AND resource = 'form' LIMIT 1";
            
            $req = $db->prepare($check_lead_sql);
            $req->execute();   
            
            $check_lead_res = $req->fetch();

            if($check_lead_res){
                $users_arr[$user_id]['checked_phones'][$lead_data['phone']] = $lead_data['phone'];
                if(isset($users_arr[$user_id]['leads'][$lead_data['phone']])){
                    unset($users_arr[$user_id]['leads'][$lead_data['phone']]);
                }
                continue;
            }



            $phone_sql = "SELECT * FROM user_phone_calls WHERE id = ".$lead_data['phone_id']."";

            $req = $db->prepare($phone_sql);
            $req->execute();   
            
            $phone_data = $req->fetch();


            $comment_sql = "SELECT * FROM misscalls_comments WHERE lead_id = ".$lead_data['id']."";

            $req = $db->prepare($comment_sql);
            $req->execute();   
            
            $comment_data = $req->fetch();
            
            $lead_data['comment'] = "";
            $lead_data['lead_by_phone'] = "";
            $lead_data['mark_color'] = "";
            $lead_data['last_comment_by_user'] = "";

			if($comment_data){
				$lead_data['comment'] = $comment_data['comment'];
				$lead_data['lead_by_phone'] = $comment_data['lead_by_phone'];
				$lead_data['mark_color'] = $comment_data['mark_color'];
				$lead_data['last_comment_by_user'] = $comment_data['last_comment_by_user'];
			}
            
            if($lead_data['last_comment_by_user'] != "" && $lead_data['last_comment_by_user']!='0'){
                $owners_list[] = $lead_data['last_comment_by_user'];
            }
            $lead_data['phone_data'] = $phone_data;
            if($phone_data['answer'] == "ANSWERED"){
                $answerd_count++;
            }
            else{
                $noanswer_count++;
            }

            $lead_data['appears'] = 1;
            $lead_data['appears_arr'] = array();
            if(isset($users_arr[$user_id]['leads'][$lead_data['phone']])){
                if($phone_data['answer'] == "ANSWERED"){
                    $doubled_answerd_count++;
                }
                else{
                    $doubled_noanswer_count++;
                }			
                
                $appears = $users_arr[$user_id]['leads'][$lead_data['phone']]['appears'];
                $appears_arr = array($users_arr[$user_id]['leads'][$lead_data['phone']]);
                
                foreach($users_arr[$user_id]['leads'][$lead_data['phone']]['appears_arr'] as $app_key=>$appear){
                    $appears_arr[$app_key] = $appear;
                }
                $lead_data['appears'] = $appears+1;
                $lead_data['appears_arr'] = $appears_arr;
    
            }
            $users_arr[$user_id]['leads'][$lead_data['phone']] = $lead_data;
        }
        
        $owners_res = false;
        if(!empty($owners_list)){

            $owners_list_in_sql = implode(",",$owners_list);
            $owners_sql = "SELECT id,full_name FROM users WHERE id IN($owners_list_in_sql)";
            
            $req = $db->prepare($owners_sql);
            $req->execute();   
            
            $owners_res = $req->fetchAll();
        }
        
        if(!$owners_res){
            $owners_res = array();
        }

        
        $owners_names = array();
        foreach($owners_res as $owner_data){
            $owners_names[$owner_data['id']] = $owner_data['full_name'];
        }

        return array(
            'filter_str'=>$filter_str,
            'users_arr'=>$users_arr,
            'owners_names'=>$owners_names,
            'answerd_count'=>$answerd_count,
            'doubled_answerd_count'=>$doubled_answerd_count,
            'noanswer_count'=>$noanswer_count,
            'doubled_noanswer_count'=>$doubled_noanswer_count,
        );
    }
}
?>