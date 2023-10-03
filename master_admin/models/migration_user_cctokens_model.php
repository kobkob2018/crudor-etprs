<?php
  class Migration_user_cctokens extends TableModel{

    private static $ilbiz_db = NULL;

    protected static $main_table = 'migration_user_cctokens';

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
        $check_tables = array("migration_user_cctokens");
        foreach($check_tables as $table){
            if($check_true){
                continue;
            }
            $sql = "SELECT user_id FROM $table WHERE user_id = :user_id LIMIT 1";
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
        $migration_user_cctokens = self::simple_get_list_by_table_name(array('user_id'=>$user_id),'migration_user_cctokens');

		if(!$migration_user_cctokens){
            $migration_user_cctokens = array();
        }
        foreach($migration_user_cctokens as $migration_user_token){
            self::simple_delete_by_table_name($migration_user_token['id'],'migration_user_cctokens');
            self::simple_delete_by_table_name($migration_user_token['token_id'],'user_cc_token');
        }
    }

    public static function do_migrate($user_id,$migration_user){
        $ilbiz_db = self::getIlbizDb();
        $sql = "SELECT * FROM userCCToken WHERE userId = :user_id";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('user_id'=>$migration_user['old_id']));
        $user_cctokens = $req->fetchAll();
        if(!$user_cctokens){
            $user_cctokens = array();
        }
        foreach($user_cctokens as $user_token){
          
            $new_user_token = array(
                'user_id'=>$user_id,
                'transaction_id'=>$user_token['transaction_id'],
                'token'=>$user_token['token'],
                'L4digit'=>$user_token['L4digit'],
                'Tmonth'=>$user_token['Tmonth'],
                'Tyear'=>$user_token['Tyear'],
                'customer_ID_number'=>$user_token['customer_ID_number'],
                'Fild1'=>$user_token['Fild1'],
                'Fild2'=>$user_token['Fild2'],
                'Fild3'=>$user_token['Fild3'],
                'full_name'=>$user_token['full_name'],
                'biz_name'=>$user_token['biz_name'],
            );
            
            $fix_utgt_arr = array('Fild1', 'Fild2', 'Fild3', 'full_name', 'biz_name');
            foreach($fix_utgt_arr as $ut){
                if($new_user_token[$ut] != ''){
                    $new_user_token[$ut] = utgt($new_user_token[$ut]);
                }
            }

            $new_user_token_id = self::simple_create_by_table_name($new_user_token,"old_user_cctokens");
            $migration_user_token = array(
                'token_id'=>$new_user_token_id,
                'user_id'=>$user_id,
                'old_id'=>$user_token['id']
            );
            self::simple_create_by_table_name($migration_user_token,"migration_user_cctokens");
           
        }
    }
}
?>