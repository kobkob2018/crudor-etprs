<?php
  class Auto_login_token extends TableModel{

    protected static $main_table = 'auto_login_token';

    public static function clean_old_tokens($days_old = '4', $expiry_type = false){
      $execute_arr = array('days_old'=>$days_old);
      $expiry_type_sql = "AND expiry_type IS NULL";
      if($expiry_type){
        $expiry_type_sql = "AND expiry_type = :expiry_type ";
        $execute_arr['expiry_type'] = $expiry_type;
      }
      $sql = "DELETE FROM auto_login_token WHERE 1 $expiry_type_sql AND created_date < (NOW() - INTERVAL :days_old DAY)";
      $db = Db::getInstance();		
      $req = $db->prepare($sql);
      $req->execute($execute_arr);
    }

  }
?>