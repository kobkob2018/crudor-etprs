<?php
  class User_pending_emails extends TableModel{

    protected static $main_table = 'user_pending_emails';

    public static function clean_month_old_emails(){
      //did it 40 days period..
      $sql = "DELETE FROM user_pending_emails WHERE created_date < (NOW() - INTERVAL 40 DAY)";
      $db = Db::getInstance();		
      $req = $db->prepare($sql);
      $req->execute();
    }

  }
?>