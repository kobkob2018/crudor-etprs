<?php
  class myleadsUser_bookkeeping extends TableModel{

    protected static $main_table = 'user_bookkeeping';

    public static function renew_hosting($user_id){
      $db = Db::getInstance();
      $sql = "UPDATE user_bookkeeping SET hostEndDate = DATE_ADD(hostEndDate, INTERVAL 1 YEAR) WHERE user_id = :user_id";
      $req = $db->prepare($sql);
      $req->execute(array('user_id'=>$user_id));
      return;
    }
    
    public static function renew_domain($user_id){
      $db = Db::getInstance();
      $sql = "UPDATE user_bookkeeping SET domainEndDate = DATE_ADD(domainEndDate, INTERVAL 1 YEAR) WHERE user_id = :user_id";
      $req = $db->prepare($sql);
      $req->execute(array('user_id'=>$user_id));
      return;
    }

  }

?>