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
        $sql = "SELECT cat_name, id, father, status, hidden FROM biz_categories WHERE status != '9' AND googleADSense = '' AND father = :cat_id";
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
				$cat['pair_lalbel'] = self::get_old_cat_label($cat['id']);
                $cat['deep'] = $deep;
                $cat_tree[] = $cat;
                $cat_tree = self::get_old_cat_tree($cat['id'],$cat_tree,$deep);
            }  
        }
		
        return $cat_tree;
    }


    public static function get_new_cat_tree($cat_id = '0', $cat_tree = array() , $deep = 0){

        $deep++;
        $db = Db::getInstance();
        $sql = "SELECT label, id, parent, active, visible FROM biz_categories WHERE parent = :cat_id";
        $req = $db->prepare($sql);
        $req->execute(array('cat_id'=>$cat_id));
        $result = $req->fetchAll();
        
        if($result){
            foreach($result as $cat){
				
                $cat['deep'] = $deep;
                $cat['pair_lalbel'] = self::get_cat_label($cat['id']);
                $cat_tree[] = $cat;
                
                $cat_tree = self::get_new_cat_tree($cat['id'],$cat_tree,$deep);
            }  
        }
		
        return $cat_tree;
    }

    public static function get_current_cat_pair($cat_id){

        $db = Db::getInstance();
        $sql = "SELECT old_cat_id FROM migration_cat WHERE cat_id = :cat_id";
        $req = $db->prepare($sql);
        $req->execute(array('cat_id'=>$cat_id));
        $result = $req->fetch();
        
        return $result;
    }   

    public static function remove_current_cat_pair($cat_id){
        $db = Db::getInstance();
        $sql = "DELETE FROM migration_cat WHERE cat_id = :cat_id";
        $req = $db->prepare($sql);
        $req->execute(array('cat_id'=>$cat_id));
    }

    public static function add_cat_pair($cat_id, $old_cat_id){
        $db = Db::getInstance();
        $sql = "INSERT INTO migration_cat(cat_id, old_cat_id) VALUES (:cat_id,:old_cat_id)";
        $req = $db->prepare($sql);
        $req->execute(array('cat_id'=>$cat_id,'old_cat_id'=>$old_cat_id));
    }

    public static $cat_labels = array();
  
    public static function get_cat_label($cat_id,$deep=0){
        if(isset(self::$cat_labels[$cat_id])){
            return self::$cat_labels[$cat_id];
        }
        $deep++;
        $db = Db::getInstance();
        $sql = "SELECT label, parent FROM biz_categories WHERE id = :cat_id";
        
        $req = $db->prepare($sql);
        $req->execute(array('cat_id'=>$cat_id));
        $result = $req->fetch();
        if(!$result){
            return "";
        }
        $label = $result['label'];
        $label_prefix = "";
        if($deep < 4 && $result['parent'] != '0'){
            $label_prefix = self::get_cat_label($result['parent'],$deep);
            if($label_prefix != ""){
                $label_prefix .= ", ";
            }
        }
        self::$cat_labels[$cat_id] = $label_prefix.$label;
        return self::$cat_labels[$cat_id];
    }

    public static $old_cat_labels = array();
  
    public static function get_old_cat_label($cat_id,$deep=0){
        if(isset(self::$old_cat_labels[$cat_id])){
            return self::$old_cat_labels[$cat_id];
        }
        $deep++;
        $ilbiz_db = self::getIlbizDb();
        $sql = "SELECT cat_name, father FROM biz_categories WHERE id = :cat_id";
        
        $req = $ilbiz_db->prepare($sql);
        $req->execute(array('cat_id'=>$cat_id));
        $result = $req->fetchAll();
        if(!$result){
            return "";
        }
        $label = utgt($result['cat_name']);
        $label_prefix = "";
        if($deep < 4 && $result['father'] != '0'){
            $label_prefix = self::get_old_cat_label($result['father'],$deep);
            if($label_prefix != ""){
                $label_prefix .= ", ";
            }
        }
        self::$old_cat_labels[$cat_id] = $label_prefix.$label;
        return self::$old_cat_labels[$cat_id];
    }
}
?>