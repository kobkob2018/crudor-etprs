<?php 
    class Whatsapp_notifications extends TableModel{
        protected static $main_table = 'whatsapp_notifications';


        public static function clear_list(){
            $db = Db::getInstance();
            $sql = "TRUNCATE TABLE  whatsapp_notifications";	
            $req = $db->prepare($sql);
            $req->execute();
        }

    }

?>