<?php 
    class Whatsapp_conversations extends TableModel{
        protected static $main_table = 'whatsapp_conversation';
        protected static $auto_delete_from_attached_tables = array(
            'whatsapp_messages'=>array(
                'table'=>'whatsapp_messages',
                'id_key'=>'connection_id'
            ),
        );  

    }

?>