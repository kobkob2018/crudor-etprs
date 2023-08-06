<?php
  class Migration_user_payments extends TableModel{

    private static $ilbiz_db = NULL;

    protected static $main_table = 'migration_user_payments';

    protected static function getIlbizDb() {
        if (!isset(self::$ilbiz_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$ilbiz_db = new PDO('mysql:host='.get_config('ilbiz_db_host').'; port='.get_config('ilbiz_db_port').'; dbname='.get_config('ilbiz_db_db').'', get_config('ilbiz_db_user'), get_config('ilbiz_db_pass'), $pdo_options);      
          }
        return self::$ilbiz_db;
    }

    public static function check_if_migration_exist($filter){
        $db = DB::getInstance();
        $check_true = false;
        $check_tables = array("migration_user_payments");
        foreach($check_tables as $table){
            if($check_true){
                continue;
            }
            $sql = "SELECT site_id FROM $table WHERE user_id = :user_id LIMIT 1";
            $req = $db->prepare($sql);
            $req->execute($filter);
            $result = $req->fetch();
            if($result){
                $check_true = true;
            }
        }
        return $check_true;
    }

    public static function delete_older($user_id){
        $migration_user_payments = self::simple_get_list_by_table_name(array('user_id'=>$user_id),'migration_user_payment');

		if(!$migration_user_payments){
            $migration_user_payments = array();
        }
        foreach($migration_user_payments as $migration_user_payment){
            self::simple_delete_by_table_name($migration_user_payment['id'],'migration_user_payment');
            self::simple_delete_by_table_name($migration_user_payment['payment_id'],'old_user_payments');
        }
    }

    public static function do_migrate($user_id,$migration_user){
        $ilbiz_db = self::getIlbizDb();
        $sql = "SELECT * FROM ilbizPayByCCLog WHERE userId = :user_id AND payGood = '2'";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('user_id'=>$migration_user['old_id']));
        $user_payments = $req->fetchAll();
        if(!$user_payments){
            $user_payments = array();
        }
        foreach($user_payments as $user_payment){
          
            $new_user_payment = array(
                'user_id'=>$user_id,
                'sumTotal'=>$user_payment['sumTotal'],
                'payDate'=>$user_payment['payDate'],
                'description'=>$user_payment['description'],
                'payGood'=>$user_payment['payGood'],
                'trans_id'=>$user_payment['trans_id'],
                'CCode'=>$user_payment['CCode'],
                'Amount_paid'=>$user_payment['Amount_paid'],
                'ACode'=>$user_payment['ACode'],
                'full_name'=>$user_payment['full_name'],
                'biz_name'=>$user_payment['biz_name']
            );

            $fix_utgt_arr = array('description','ACode','full_name','biz_name');
            foreach($fix_utgt_arr as $ut){
                if($new_user_payment[$ut] != ''){
                    $new_user_payment[$ut] = utgt($new_user_payment[$ut]);
                }
            }

            $new_user_payment_id = self::simple_create_by_table_name($new_user_payment,"old_user_payments");
            $migration_user_payment = array(
                'payment_id'=>$new_user_payment_id,
                'user_id'=>$user_id,
                'old_id'=>$user_payment['id']
            );
            self::simple_create_by_table_name($migration_user_payment,"migration_user_payment");
           
        }
    }
}
?>