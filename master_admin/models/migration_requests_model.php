<?php
  class Migration_requests extends TableModel{

    private static $ilbiz_db = NULL;

    private static $leads_bk_db = NULL;
    
    protected static $main_table = 'migration_requests';

    protected static function getLeadsDb() {
        if (!isset(self::$leads_bk_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$leads_bk_db = new PDO('mysql:host='.get_config('db_host').';dbname=ilbiz_leads_backup', get_config('db_user'), get_config('db_password'), $pdo_options);      
          }
        return self::$leads_bk_db;
    }

    protected static function getIlbizDb() {
        if (!isset(self::$ilbiz_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$ilbiz_db = new PDO('mysql:host='.get_config('ilbiz_db_host').'; port='.get_config('ilbiz_db_port').'; dbname='.get_config('ilbiz_db_db').'', get_config('ilbiz_db_user'), get_config('ilbiz_db_pass'), $pdo_options);      
          }
        return self::$ilbiz_db;
    }

    public static function test_db_connection(){
        $bk_db = self::getLeadsDb();
        $sql = "SELECT * FROM test_1 WHERE 1";
        $req = $bk_db->prepare($sql);
        $req->execute();
        $res = $req->fetchAll();
        print_r_help($res);
        exit("ok now");
        return $res;
    }

    public static function delete_older(){
        $sql = "DELETE FROM estimate_form WHERE 1";
        $bk_db = self::getLeadsDb();
        $req = $bk_db->prepare($sql);
        $req->execute();
    }

    public static function do_migrate_requests(){
        $bk = self::getLeadsDb();
        $ilbiz_db = self::getIlbizDb();

        $return_array = array('status'=>'done');

        $latest_migrate_request_id = '0';
        $sql = "SELECT id FROM estimate_form ORDER BY id desc LIMIT 1";
        $req = $bk->prepare($sql);
        $req->execute();
        $latest_migrate_request = $req->fetch();
        if($latest_migrate_request){
            $latest_migrate_request_id = $latest_migrate_request['id'];
        }

        //migrate requests
        $sql = "SELECT * FROM estimate_form WHERE id > :latest_id LIMIT 2000";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('latest_id'=>$latest_migrate_request_id));
        $requests = $req->fetchAll();
        if(!$requests){
            $requests = array();
        }
        if(empty($requests)){
            return $return_array;
        }

        $return_array['count'] = count($requests);
        $return_array['status'] = 'found_requests';

        $params_arr = array(
            'id',
            'cat_f',
            'cat_s' ,
            'cat_spec',
            'name',
            'city',
            'email',
            'phone',
            'note',
            'send_to',
            'status',
            'insert_date',
            'referer',
            'show_in_mysave',
            'statistic_id',
            'passengers',
            'to_city',
            '10service_group_product_id',
            'open_by_credit',
            'ip',
            'agent',
            'campaign_type',
            'is_mobile',
            'banner_id',
            'form_resource',
            'fb_lead_id',
            'uniq_track',

        );

        $utf_arr = array(
            'city',
            'email' ,
            'phone',
            'note',
            'send_to',
            'referer',
            'passengers',
            'to_city',
            'ip',
            'agent',
            'form_resource',
            'uniq_track',
        );

        foreach($requests as $request){
            foreach($utf_arr as $key){
                if($request[$key] != ''){
                    try{
                        $request[$key] = utgt($request[$key]);     
                    } 
                    catch (Exception $e) {
                    }     
                }
            }
            $new_request = array();
            foreach($params_arr as $key){
                $new_request[$key] = $request[$key];
            }

            try{
                $new_request_id = self::alt_create_by_table_name($new_request,"estimate_form");
            }
            catch (Exception $e) {

            }

            if(!isset($return_array['first'])){
                $return_array['first'] = $request['id'];
            }
            $return_array['last'] = $request['id'];

        }
        return $return_array;
    }


    public static function alt_create_by_table_name($field_values, $table_name){
      
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
  
        $bk_db = self::getLeadsDb();
   
        $req = $bk_db->prepare($sql);
        $req->execute($execute_arr);
        return $bk_db->lastInsertId();
      }
}
?>