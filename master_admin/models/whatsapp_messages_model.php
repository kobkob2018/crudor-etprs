<?php 
    class Whatsapp_messages extends TableModel{
        protected static $main_table = 'whatsapp_messages';  

        public static $fields_collection = array(
            'campaign_type'=>array(
                'label'=>'סוג המסר',
                'type'=>'select',
                'default'=>'text',
                'options'=>array(
                    array('value'=>'text', 'title'=>'טקסט רגיל'),
                    array('value'=>'template', 'title'=>'תבנית')
                )
            ),
            'messages_text'=>array(
                'label'=>'טקסט \ תבנית',
                'type'=>'text',
            ),
        );
    }

    

?>