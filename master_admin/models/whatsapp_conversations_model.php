<?php 
    class Whatsapp_conversations extends TableModel{
        protected static $main_table = 'whatsapp_conversations';
        protected static $auto_delete_from_attached_tables = array(
            'whatsapp_messages'=>array(
                'table'=>'whatsapp_messages',
                'id_key'=>'conversation_id'
            ),
        );

        public static $fields_collection = array(
            'contact_custom_name'=>array(
                'label'=>'שם איש הקשר',
                'type'=>'text',
            ),
        );

    }

?>