<?php 
    class Whatsapp_messages_errors extends TableModel{
        protected static $main_table = 'whatsapp_messages_errors';  

        public static function get_last_error_id($conversation_id){
            $db = DB::getInstance();
            $sql = "SELECT id FROM whatsapp_messages_errors WHERE conversation_id = :conversation_id ORDER BY id desc LIMIT 1";
            $req = $db->prepare($sql);
            $req->execute(array('conversation_id'=>$conversation_id));
            $result = $req->fetch();
            if(!$result){
                return '0';
            }
            return $result['id'];
        }

        public static function get_conversation_new_errors($conversation_id, $last_err_viewed){
            $db = DB::getInstance();
            $sql = "SELECT * FROM whatsapp_messages_errors WHERE conversation_id = :conversation_id AND id > :last_err_viewed";
            $req = $db->prepare($sql);
            $req->execute(array('conversation_id'=>$conversation_id,'last_err_viewed'=>$last_err_viewed));
            $result = $req->fetchAll();
            if((!$result) || !is_array($result)){
                return array();
            }
            return $result;
        }
    }

    

?>