<?php

  class User_current_phone_calls extends TableModel{
    protected static $main_table = 'user_current_phone_calls';

    public static function insert_call($call){
      $user_id = self::get_user_by_call($call);
      if(!$user_id){
        return;
      }
      $call_data = array(
        'user_id'=>$user_id,
        'call_from'=>$call['call_from'],
        'call_to'=>$call['call_to'],
        'did'=>$call['did'],
        'link_sys_identity'=>$call['link_sys_identity'],
      );
      self::create($call_data);
    }

    protected static function get_user_by_call($call){
      $db = Db::getInstance();
      $did = isset($call['did'])? $call['did'] : "" ;
      if((!$did) || $did == ''){
        return false;
      }

      $execute_arr = array('number_find'=>$did);
      $sql = "SELECT * FROM user_phones WHERE number = :number_find LIMIT 1";
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
      $user_phone = $req->fetch();
      if(!$user_phone){
        return false;
      }
      return $user_phone['user_id'];

    }

    public static function cleanup_20_minutes(){
      $db = Db::getInstance();
      $sql = "DELETE FROM user_current_phone_calls WHERE call_date < DATE_SUB( NOW( ) , INTERVAL 20 MINUTE )";
      $req = $db->prepare($sql);
      $req->execute();
    }

  }

?>