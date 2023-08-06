<?php
  class Migration_user extends TableModel{

    private static $ilbiz_db = NULL;

    protected static $main_table = 'migration_user';

    protected static function getIlbizDb() {
        if (!isset(self::$ilbiz_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$ilbiz_db = new PDO('mysql:host='.get_config('ilbiz_db_host').'; port='.get_config('ilbiz_db_port').'; dbname='.get_config('ilbiz_db_db').'', get_config('ilbiz_db_user'), get_config('ilbiz_db_pass'), $pdo_options);      
          }
        return self::$ilbiz_db;
    }

    public static $fields_collection = array(

        'old_user'=>array(
            'label'=>'מספר משתמש במערכת ישנה',
            'type'=>'text',
            'validation'=>'required'
        ),

    );

    public static function get_old_user_data_by_id($user_id){
        $ilbiz_db = self::getIlbizDb();
        $sql = "select id, full_name, unk, name from users WHERE id = :user_id";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('user_id'=>$user_id));
        $result = $req->fetch();

        if($result){
			$name = utgt($result['name']);
            return array(
                'user_id'=>$result['id'],
                'unk'=>$result['unk'],
                'name'=>utgt($result['name']),
                'full_name'=>utgt($result['full_name'])
            );
        }
        return false;
    } 

    public static function get_user_by_unk($unk){
        $ilbiz_db = self::getIlbizDb();
        $sql = "select id, full_name, unk, name from users WHERE unk = :unk";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('unk'=>$unk));
        $result = $req->fetch();
        return $result;
    }
}
?>