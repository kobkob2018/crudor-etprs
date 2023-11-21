<?php
  class Model {

	//all model classes extand Model
    public function __construct() {
		
    }

    public static function simple_update_by_table_name($row_id, $field_values, $table_name){
      $fields_sql_arr = array();
      $execute_arr = array('row_identifier'=>$row_id);
      foreach($field_values as $key=>$value){
          $fields_sql_arr[] = "$key = :$key";
          $execute_arr[$key] = $value;
      }
      
      $fields_sql = implode(",",$fields_sql_arr);
      $sql = "UPDATE $table_name SET $fields_sql WHERE id = :row_identifier";
      $db = Db::getInstance();		
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
    }

    public static function simple_delete_by_table_name($row_id, $table_name){

      $execute_arr = array('row_identifier'=>$row_id);
      
      $sql = "DELETE FROM $table_name WHERE id = :row_identifier ";
      $db = Db::getInstance();		
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
    } 

    public static function simple_delete_list_by_table_name($item_list_in_str, $table_name){
      //$execute_arr = array('item_list_in_str'=>$item_list_in_str);
      $sql = "DELETE FROM $table_name WHERE id IN ($item_list_in_str) ";
      $db = Db::getInstance();		
      $req = $db->prepare($sql);
      $req->execute();
    }

    public static function simple_create_by_table_name($field_values, $table_name){
      
      $fields_keys_sql_arr = array();
      $fields_values_sql_arr = array();
      $execute_arr = array();
      foreach($field_values as $key=>$value){
          $fields_keys_sql_arr[] = " $key";
          $fields_values_sql_arr[] = " :$key";
          $execute_arr[$key] = $value;
      }
      $fields_keys_sql = implode(",",$fields_keys_sql_arr);
      $fields_values_sql = implode(",",$fields_values_sql_arr);
      $sql = "INSERT INTO $table_name ($fields_keys_sql) VALUES($fields_values_sql)";

      $db = Db::getInstance();		
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      return $db->lastInsertId();
    }

    public static function simple_find_by_table_name($filter_arr,$table_name , $select_params = "*", $payload = array()){
      $req = static::simple_find_with_filter_req_by_table_name($filter_arr,$table_name, $select_params, $payload);
      if(!isset($payload['NO_FETCH_ASSOC'])){
        $result = $req->fetch(PDO::FETCH_ASSOC);
      }
      else{
        $result = $req->fetch();
      }
      if(isset($payload['add_custom_param'])){
        $add_custom_param = $payload['add_custom_param'];
        $controller_interface = $add_custom_param['controller'];
        $method = $add_custom_param['method'];
        $more_info = array();
        if(isset($add_custom_param['more_info'])){
          $more_info = $add_custom_param['more_info'];
        }
        $result = $controller_interface->$method($result,$more_info);
      }
      return $result;

    }

    public static function simple_get_list_by_table_name($filter_arr,$table_name, $select_params = "*", $payload = array()){
      $req_return = static::simple_find_with_filter_req_by_table_name($filter_arr,$table_name, $select_params, $payload);
      
      if(is_array($req_return)){
        $req = $req_return['req'];
      }
      else{
        $req = $req_return;
      }

      if(!isset($payload['NO_FETCH_ASSOC'])){
        $list = $req->fetchAll(PDO::FETCH_ASSOC);
      }
      else{
        $list = $req->fetchAll();
      }
      if(isset($payload['add_custom_param'])){
        $add_custom_param = $payload['add_custom_param'];
        $controller_interface = $add_custom_param['controller'];
        $method = $add_custom_param['method'];
        $more_info = array();
        if(isset($add_custom_param['more_info'])){
          $more_info = $add_custom_param['more_info'];
        }
        foreach($list as $key=>$item){
          $list[$key] = $controller_interface->$method($item, $more_info);
        }      
      }
      if(is_array($req_return)){
        return array(
          'paging'=>$req_return['paging'],
          'list'=>$list
        );
      }   
      return $list;
    }
    
    protected static function simple_find_with_filter_req_by_table_name($filter_arr,$table_name, $select_params = "*", $payload = array()){
      $db = Db::getInstance();		
      $fields_sql_arr = array('1');
      $execute_arr = array();
      foreach($filter_arr as $key=>$value){
        if(is_null($value)){
          $fields_sql_arr[] = " $key IS NULL ";
        }
        elseif(is_array($value)){
          if(isset($value['str_like']) && isset($value['columns_like'])){
            if($value['str_like'] != ""){
              $execute_arr[$key] = "%".$value['str_like']."%";
              $columns_like_sql_arr =  array();
              foreach($value['columns_like'] as $column_like){
                $columns_like_sql_arr[] = " $column_like LIKE (:$key) ";
              }
            }
            $columns_like_sql = implode(" OR ",$columns_like_sql_arr);
            $fields_sql_arr[] = "($columns_like_sql)";
          }
          else{
            $in_items_arr = array();
            foreach($value as $in_key=>$in_var){
              $item_key = $key."_".$in_key;
              $in_items_arr[] = ":".$item_key;
              $execute_arr[$item_key] = $in_var;
            }
            $in_sql = implode(", ",$in_items_arr);
            $fields_sql_arr[] = "$key IN ($in_sql)";
          }

        }
        else{

            $fields_sql_arr[] = "$key = :$key";
            $execute_arr[$key] = $value;
        }
      }
      
      $fields_sql = implode(" AND ",$fields_sql_arr);
      $order_by_sql = '';
      if(isset($payload['order_by'])){
        $order_by_sql = " ORDER BY " . $payload['order_by'];
      }
      $limit_sql = "";
      $paging_result = false;
      if(isset($payload['pagination'])){
        
        $pagination_arr = self::create_pagination_arr($payload['pagination']);
        $page_start = intval($pagination_arr['page']) - 1;
        $limit_start = $page_start*intval($pagination_arr['page_limit']);
        $limit_sql = "LIMIT ".$limit_start.", ".$pagination_arr['page_limit'];
        $paging_sql = "SELECT COUNT(".$pagination_arr['count_by'].") as row_count FROM $table_name WHERE $fields_sql";
        
        $paging_req = $db->prepare($paging_sql);
        $paging_req->execute($execute_arr);
        $paging_result = $paging_req->fetch();
        $row_count = $paging_result['row_count'];
        $page_count = intval($row_count)/intval($pagination_arr['page_limit']);
        $page_count = floor($page_count);
        $not_exact_count = intval($row_count)%intval($pagination_arr['page_limit']);
        if($not_exact_count){
          $page_count += 1;
        }
        
        $paging_result['page_count'] = $page_count;
        $paging_result['page'] = $pagination_arr['page'];
        $paging_result['page_limit'] = $pagination_arr['page_limit'];
      }
      $sql = "SELECT $select_params FROM $table_name WHERE $fields_sql $order_by_sql $limit_sql";
    
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      if(!$paging_result){
        return $req;
      }
      return array(
        'req'=>$req,
        'paging'=>$paging_result
      );
    }

    //create defult values for pagination
    protected static function create_pagination_arr($payload_pagination){
      $pagination_arr = array(
        'page'=>'1',
        'page_limit'=>'50',
        'count_by'=>'id'
      );
      foreach($payload_pagination as $key=>$val){
        $pagination_arr[$key] = $val;
      }
      return $pagination_arr;
    }

    public static function simple_delete_arr_by_table_name($item_arr, $table_name){
      $item_ids_arr = array();
      foreach($item_arr as $item){
          if(isset($item['id'])){
              $item_ids_arr[] = $item['id'];
          }
      }
      $item_list_in_str = implode(", ",$item_ids_arr);
      $delete_result = self::simple_delete_list_by_table_name($item_list_in_str, $table_name);
      
      //could use for further delete at related tables
      return array(
        'deleted'=>$delete_result,
        'item_ids_arr'=>$item_ids_arr,
        'item_list_in_str'=>$item_list_in_str
      );
    }   

    public static function simple_get_children_list_of_by_table_name($parent_id, $table_name, $select_params = "*", $filter_arr = array(), $payload = array()){
      $filter_arr['parent'] = $parent_id;
      return self::simple_get_list_by_table_name($filter_arr, $table_name, $select_params, $payload);
    }

    public static function simple_delete_with_offsprings_by_table_name($row_id, $table_name){
      $item_offsprings = self::simple_get_item_offsprings_by_table_name($row_id, $table_name, 'parent, id');
      $item_offsprings[] = array('id'=>$row_id);

      return self::simple_delete_arr_by_table_name($item_offsprings,$table_name);
    }

    public static function simple_get_item_offsprings_by_table_name($item_id, $table_name, $select_params = "*", $filter_arr = array(), $payload = array(), $recursive_arr = array(), $generation = 0, $item = false){
      $generation++;
      $children_arr = array();
      $item_children = self::simple_get_children_list_of_by_table_name($item_id, $table_name, $select_params, $filter_arr, $payload);

      if(is_array($item_children)){
          foreach($item_children as $child_item){
              $child_item['generation'] = $generation;
              $children_arr[] = $child_item;
          }
      }
      foreach($children_arr as $child_item){
          $recursive_arr = self::simple_get_item_offsprings_by_table_name($child_item['id'],$table_name, $select_params, $filter_arr, $payload, $recursive_arr, $generation, $child_item);
      }
      if($item){
        
          $recursive_arr[] = $item;
      }
      return $recursive_arr;
    }


    public static function simple_get_item_offsprings_tree_by_table_name($item_id, $table_name, $select_params = "*", $filter_arr = array(), $payload = array(), $generation = 0){
      $generation++;
      $children_arr = array();
      $item_children = self::simple_get_children_list_of_by_table_name($item_id, $table_name, $select_params, $filter_arr, $payload);
      if(is_array($item_children)){
          foreach($item_children as $child_item){
              $child_item['generation'] = $generation;
              $child_item['children'] = self::simple_get_item_offsprings_tree_by_table_name($child_item['id'], $table_name, $select_params, $filter_arr, $payload, $generation);
              if(empty($child_item['children'])){
                $child_item['has_children'] = false;
              }
              else{
                $child_item['has_children'] = true;
              }
              $children_arr[] = $child_item;
          }
      }
      return $children_arr;
    }

    public static function simple_get_item_parents_tree_by_table_name($item_id, $table_name, $select_params = "*", $recursive_arr = array(), $deep = 0){
      $select_params_with_parent = $select_params;
      if($select_params != '*'){
          $select_params_with_parent = "parent, ".$select_params;
      }
      $filter_arr = array('id'=>$item_id);
      $item_data = self::simple_find_by_table_name($filter_arr, $table_name ,$select_params_with_parent);
      if(is_array($item_data)){
          $item_data['deep'] = $deep;
      }
      $deep++;
      if($deep > 10){
          exit("something here is wrong brother");
      }
      $curren_count = count($recursive_arr);
      if($item_data && $item_data['parent'] != '0'){
          $recursive_arr = self::simple_get_item_parents_tree_by_table_name($item_data['parent'], $table_name, $select_params, $recursive_arr, $deep);
      }
      if(is_array($item_data)){
        if($curren_count + 1 == $deep){
            $item_data['is_current'] = true;
        }
        else{
            $item_data['is_current'] = false;
        }
          $item_data['op_deep'] = count($recursive_arr);
          $recursive_arr[] = $item_data;
      }
      
      return $recursive_arr;
    }

    public static function simple_rearange_priority_by_table_name($filter_arr, $table_name){
      $item_list = self::simple_get_list_by_table_name($filter_arr, $table_name, 'id, priority', array('order_by'=>'priority'));
      $priority = 0;
      foreach($item_list as $item){
        $priority++;
        $update_arr = array(
          'id'=>$item['id'],
          'priority'=>$priority
        );
        $sql = "UPDATE $table_name SET priority = :priority WHERE id = :id";
        $db = Db::getInstance();		
        $req = $db->prepare($sql);
        $req->execute($update_arr);
      }
    }

    public static function simple_get_priority_space_by_table_name($filter_arr, $item_id, $table_name){

      $item_info = false;
      if($item_id == '-1'){
        return self::simple_get_priority_top_space_by_table_name($filter_arr, $table_name);
      }
      if($item_id != '0'){
        $item_info = self::simple_find_by_table_name(array('id'=>$item_id),$table_name,'id, priority');
      }
      $space_priority = 0;

      if($item_info){
        $space_priority = $item_info['priority'];
      }
      
      $fields_sql_arr = array(' priority >= :space_priority ');
      $execute_arr = array('space_priority'=>$space_priority);
      foreach($filter_arr as $key=>$value){
          $fields_sql_arr[] = "$key = :$key";
          $execute_arr[$key] = $value;
      }
      
      $fields_sql = implode(" AND ",$fields_sql_arr);


      $sql = "UPDATE $table_name SET priority = priority+1 WHERE $fields_sql";
      $db = Db::getInstance();		
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      return $space_priority;
    }    

    public static function simple_get_priority_top_space_by_table_name($filter_arr, $table_name){
      $fields_sql_arr = array(' 1 ');
      $execute_arr = array();
      foreach($filter_arr as $key=>$value){
          $fields_sql_arr[] = "$key = :$key";
          $execute_arr[$key] = $value;
      }
      
      $fields_sql = implode(" AND ",$fields_sql_arr);


      $sql = "SELECT priority FROM $table_name WHERE $fields_sql ORDER BY priority desc LIMIT 1";
      $db = Db::getInstance();		
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      $item_data = $req->fetch();
      if($item_data){
        return $item_data['priority'] + 1;
      }
      return '0';
    }

    public static function get_select_user_options(){
      $db = Db::getInstance();
      $sql = "SELECT id, full_name FROM users order by full_name";
      $req = $db->prepare($sql);
      $sql_arr = array();
      $req->execute($sql_arr);
      $user_list = $req->fetchAll();
      $return_options = array();
      foreach($user_list as $user){
          $return_options[] = array('value'=>$user['id'],'title'=>$user['full_name']);
      }
      return $return_options;
    }

    public static function get_select_yes_no_options(){
      return array(
          array('value'=>'0', 'title'=>'לא'),
          array('value'=>'1', 'title'=>'כן')
      );
    }    

  }
?>