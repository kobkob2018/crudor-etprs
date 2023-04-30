<?php
  class MasterUser_leads extends TableModel{

    protected static $main_table = 'user_leads';

    public static function add_user_lead($lead_info,$user_id){
        $lead_fixed_values = array(
            'user_id'=>$user_id,
            'full_name'=>$lead_info['full_name'],
            'email'=>$lead_info['email'],
            'phone'=>$lead_info['phone'],
            'note'=>$lead_info['note'],
            'extra'=>$lead_info['extra_info'],
            'open_state'=>$lead_info['open_state'],
            'request_id'=>$lead_info['request_id'],
            'token'=>$lead_info['token'],
            'send_state'=>$lead_info['send_state'],
            'resource'=>$lead_info['resource'],
            'billed'=>$lead_info['billed'],
        );
        return self::create($lead_fixed_values);
    }

    public static function get_users_lead_list($filter){
      $db = Db::getInstance();
      $where_arr = self::get_where_arr($filter);
      $where_str = $where_arr['where_str'];
      $where_params = $where_arr['where_params'];
      
      //count all
      $sql = "SELECT COUNT(ul.id) as row_count FROM user_leads ul WHERE $where_str"; 		
      $req = $db->prepare($sql);
      $req->execute($where_params);
      $row_count_result =  $req->fetch();
      $row_count = '0';
      if($row_count_result){
          $row_count = $row_count_result['row_count'];
      }
      $row_count = intval($row_count);
      //get rows in page
      $sql = "SELECT ul.*, u.biz_name as biz_name , u.full_name as user_name FROM user_leads ul LEFT JOIN users u ON u.id = ul.user_id WHERE $where_str ORDER BY id desc "; 		
      $sql.= " ".$where_arr['limit_str'];
      $req = $db->prepare($sql);
      $req->execute($where_params);
      $users_leads =  $req->fetchAll();
      $return_arr = array(
          'row_count'=>$row_count,
          'users_leads'=>$users_leads
      );
      return $return_arr;
    }


    protected static function get_where_arr($filter){
      $where_str = "1";
      $where_params = array();
      if($filter['status'] != 'all'){
          $where_str .= " AND ul.status = :status ";
          $where_params['status'] = $filter['status'];
      }
      if($filter['date_s'] != ''){
          $where_str .= " AND ul.date_in >= :date_s ";
          $where_params['date_s'] = $filter['date_s'];
      }

      if($filter['free'] != ''){
          $where_str .= " AND (ul.phone LIKE :free OR ul.email LIKE :free OR ul.full_name LIKE :free) ";
          $where_params['free'] = "%".$filter['free']."%";
      }
      if($filter['filter_selected_users'] == '1'){
          $where_str .= " AND ul.user_id IN (SELECT distinct user_id FROM user_lead_visability WHERE show_in_leads_report = '1') ";
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

    public static function bulk_update_status($status,$lead_id_arr){
      
      $lead_id_in = implode(",",$lead_id_arr);
      $execute_arr = array('status'=>$status);
      $db = Db::getInstance();
      $sql = "UPDATE user_leads  SET status = :status WHERE id IN ($lead_id_in)";
      $req = $db->prepare($sql);
      return $req->execute($execute_arr);
    }
}
?>