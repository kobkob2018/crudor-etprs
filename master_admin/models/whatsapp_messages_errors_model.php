<?php 
    class Whatsapp_messages_errors extends TableModel{
        protected static $main_table = 'whatsapp_messages_errors';  

        public static function get_last_error_id($conversation_id){
            $db = DB::getInstance();
            $sql = "SELECT id FROM whatsapp_messages_errors ORDER BY id desc LIMIT 1";
            $req = $db->prepare($sql);
            $req->execute();
            $result = $req->fetch();
            if(!$result){
                return '0';
            }
            return $result['id'];
        }
    }

    

?>