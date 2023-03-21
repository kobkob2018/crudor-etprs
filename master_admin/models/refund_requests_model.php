<?php
  class Refund_requests extends TableModel{

    protected static $main_table = 'refund_requests';

    public static function get_lead_status($lead_id){
        $execute_arr = array('lead_id'=>$lead_id);
        $db = Db::getInstance();
		$sql = "SELECT status FROM user_leads WHERE id = :lead_id";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        $result = $req->fetch();
        if($result){
            return $result['status'];
        }
        else{
            return false;
        }
    }

    public static function deny_refund($refund_id, $comment){
        $execute_arr = array('refund_id'=>$refund_id ,'comment'=>$comment);
        $db = Db::getInstance();

		$sql = "update lead_refund_requests set denied = 1,admin_comment=:comment WHERE id = :refund_id";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        return;
    }

    public static function set_lead_status($lead_id,$status){
        $execute_arr = array('lead_id'=>$lead_id, 'status'=>$status);
        $db = Db::getInstance();
		$sql = "update user_leads set status = :status WHERE id = :lead_id";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        return $req->rowCount();
    }

    public static function get_lead_data($lead_id){
        $execute_arr = array('lead_id'=>$lead_id);
        $db = Db::getInstance();
		$sql = "SELECT * FROM user_leads WHERE id = :lead_id";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        return $req->fetch();
    }     

    public static function add_credit_to_user($user_id){
        $execute_arr = array('user_id'=>$user_id);
        $db = Db::getInstance();
		$sql = "update user_lead_settings set lead_credit = lead_credit + 1 WHERE user_id = :user_id";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        return $req->rowCount();
    }

    public static function remove_credit_to_user($user_id){
        $execute_arr = array('user_id'=>$user_id);
        $db = Db::getInstance();
		$sql = "update user_lead_settings set lead_credit = lead_credit - 1 WHERE user_id = :user_id";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        return $req->rowCount();
    }

    public static function example($lead_id,$user_id = '0'){
        $execute_arr = array('lead_id'=>$lead_id,'user_id'=>$user_id);
        $db = Db::getInstance();
		$sql = "";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        $result = $req->fetch();
        if($result){
            return $result;
        }
        else{
            return false;
        }
    }


    public static function get_refund_reasons_id_indexed(){
        $db = Db::getInstance();
		$sql = "SELECT * FROM refund_reasons";
		$req = $db->prepare($sql);
		$req->execute();
        $result = $req->fetchAll();
        $refund_reasons = array();
        foreach($result as $reason){
            $refund_reasons[$reason['id']] = $reason['label'];
        }
        return $refund_reasons;
    }

    public static function get_filtered_requests_count($user_name_filter,$s_date_filter,$e_date_filter,$page_id,$limit_rows){
        $return_array = array();
        
        $db = Db::getInstance();
        $date_from_sql = ( $s_date_filter ) ? " AND refund.request_time >= '".$s_date_filter."' " : "";
        $date_to_sql = ( $e_date_filter ) ? " AND refund.request_time <= '".$e_date_filter."' " : "";
        $user_name_sql = ( $user_name_filter ) ? " AND (user.full_name LIKE ('%".$user_name_filter."%') OR user.full_name LIKE ('%".$user_name_filter."%') ) " : "";
        $where_sql = " WHERE 1 ".$date_from_sql.$date_to_sql.$user_name_sql;
        
        $count_sql = "SELECT COUNT(distinct refund.row_id) as rows_count
                FROM lead_refund_requests refund
                LEFT JOIN users user ON refund.user_id = user.id
            ".$where_sql;
        $req = $db->prepare($count_sql);
		$req->execute();
        $result = $req->fetch();

        $return_array['rows_count'] =  $result['rows_count'];

        if($page_id == '0'){
            $page_id = '1';
        }

        $limit_row = ($page_id-1)*$limit_rows;

        $limit_sql = " LIMIT ".$limit_row.", ".$limit_rows." ";

        $sql = "SELECT distinct refund.row_id as lead_id
                FROM lead_refund_requests refund
                LEFT JOIN users user ON refund.user_id = user.id
        ".$where_sql." ORDER BY refund.id desc ".$limit_sql;
       
        $req = $db->prepare($sql);
        $req->execute();
        $refund_requests_ids = $req->fetchAll();

        $row_ids = array();
        foreach($refund_requests_ids as $refund_request){
            $row_ids[] = $refund_request['lead_id'];
        }
        $row_ids_in = implode(",",$row_ids);

        $refund_requests = array();

        if($row_ids_in != ""){

            
            $sql = "SELECT 	user.full_name as user_name, 
                        user.id as user_id, 
                        ul.request_id as biz_request_id,
                        ul.resource as resource,
                        ul.phone_id as phone_id,
                        ul.id as lead_id,
                        refund.id as refund_id,
                        ul.status as status,
                        ul.billed as billed,
                        ul.tag as tag,
                        refund.reason as reason,
                        refund.denied as denied,
                        refund.admin_comment as admin_comment,
                        refund.request_time as request_time,
                        refund.comment as comment,
                        ul.full_name as sender_name,
                        ul.phone as sender_phone,
                        ul.email as sender_email,
                        ul.date_in as send_time,
                        brq.note as brq_note,
                        brq.cat_id as cat_id

                FROM lead_refund_requests refund
                LEFT JOIN user_leads ul ON refund.row_id = ul.id
                LEFT JOIN biz_requests brq ON ul.request_id = brq.id
                LEFT JOIN users user ON ul.user_id = user.id
                WHERE refund.row_id IN (".$row_ids_in.") ORDER BY refund.id desc ";

                $req = $db->prepare($sql);
                $req->execute();
                $refund_requests = $req->fetchAll();

        }
        foreach($refund_requests as $request_key=>$request){
            $cat_id = $request['cat_id'];
            $cat_str = "";
            if($request['cat_id'] != ''){
                $cat_str = self::get_full_cat_tree_name($cat_id);
            }
            $request['cat_str'] = $cat_str;
            $refund_requests[$request_key] = $request;
        }

        $return_array['list'] = $refund_requests;

        return $return_array;
    }

    protected static $cat_tree_names = array();

    protected static function get_full_cat_tree_name($cat_id){
        if(isset(self::$cat_tree_names[$cat_id])){
            return self::$cat_tree_names[$cat_id];
        }
        
        $execute_arr = array('cat_id'=>$cat_id);
        $db = Db::getInstance();
		$sql = "SELECT id, parent, label FROM biz_categories WHERE id = :cat_id";
		$req = $db->prepare($sql);
		$req->execute($execute_arr);
        $cat_data = $req->fetch();
        if(!$cat_data){
            return "";
        }
        $cat_name = $cat_data['label'];
        if($cat_data['parent'] != '0'){
            $cat_parent_name = self::get_full_cat_tree_name($cat_data['parent']);
            if($cat_parent_name && $cat_parent_name != ''){
                $cat_name = $cat_parent_name. ", ".$cat_name;
            }
        }
        self::$cat_tree_names[$cat_id] = $cat_name;
        return self::$cat_tree_names[$cat_id];
    }

    public static function get_tag_name($tag_id){
        $db = Db::getInstance();
        $execute_arr = array('tag_id'=>$tag_id);
        $sql = "SELECT * FROM user_lead_tag WHERE id = :tag_id";

        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $tag_data = $req->fetch();
        if(!$tag_data){
            return "";
        }
        return $tag_data['tag_name'];
    }

    public static function get_phone_call_data($phone_id){
        $db = Db::getInstance();
        $execute_arr = array('phone_id'=>$phone_id);
        $sql = "SELECT * FROM user_phone_calls WHERE id = :phone_id";

        $req = $db->prepare($sql);
        $req->execute($execute_arr);
        $call_data = $req->fetch();
        return $call_data;
    }
}
?>