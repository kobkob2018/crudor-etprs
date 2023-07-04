<?php
  class Migration_cat extends TableModel{

    private static $ilbiz_db = NULL;

    protected static $main_table = 'migration_cat';

    protected static function getIlbizDb() {
        if (!isset(self::$ilbiz_db)) {
          $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
          self::$ilbiz_db = new PDO('mysql:host='.get_config('ilbiz_db_host').'; port='.get_config('ilbiz_db_port').'; dbname='.get_config('ilbiz_db_db').'', get_config('ilbiz_db_user'), get_config('ilbiz_db_pass'), $pdo_options);      
          }
        return self::$ilbiz_db;
    }

    public static function get_old_cat_tree($cat_id = '0', $cat_tree = array() , $deep = 0){

        $deep++;
        $ilbiz_db = self::getIlbizDb();
        $sql = "SELECT cat_name, id, father FROM biz_categories WHERE status != '9' AND googleADSense = '' AND father = :cat_id";
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('cat_id'=>$cat_id));
        $result = $req->fetchAll();

        $formated_params = array(
            'cat_name'
        );
        if($result){
            foreach($result as $cat){
                foreach($formated_params as $param){
					if($cat[$param] != ""){
						$cat[$param] = utgt($cat[$param]);
					}
				}
				
                $cat['deep'] = $deep;
                $cat_tree[] = $cat;
            }  
        }
		
        return $cat_tree;
    }
}
?>