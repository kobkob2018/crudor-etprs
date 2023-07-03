<?php
  class Site_migration extends TableModel{

    private static $ilbiz_db = NULL;

    protected static $main_table = 'site_migration';

    protected static function getIlbizDb() {
        if (!isset(self::$ilbiz_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$ilbiz_db = new PDO('mysql:host='.get_config('ilbiz_db_host').'; port='.get_config('ilbiz_db_port').'; dbname='.get_config('ilbiz_db_db').'', get_config('ilbiz_db_user'), get_config('ilbiz_db_pass'), $pdo_options);      
          }
        return self::$ilbiz_db;
    }

    public static $fields_collection = array(

        'old_domain'=>array(
            'label'=>'דומיין של אתר במערכת הישנה',
            'type'=>'text',
            'validation'=>'required'
        ),

    );

    public static function get_old_site_data_by_domain($domain){
        $ilbiz_db = self::getIlbizDb();
        $sql = "select id, domain, unk, name from users WHERE domain = :domain";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('domain'=>$domain));
        $result = $req->fetch();
        
        if($result){
            return array(
                'domain'=>$result['domain'],
                'unk'=>$result['unk'],
                'site_id'=>$result['id'],
                'title'=>$result['name']
            );
        }
        return false;
    } 
}
?>