<?php 
    class Whatsapp_messages extends TableModel{
        protected static $main_table = 'whatsapp_messages';  

        public static $fields_collection = array(
            'message_type'=>array(
                'label'=>'סוג המסר',
                'type'=>'select',
                'default'=>'text',
                'options'=>array(
                    array('value'=>'text', 'title'=>'טקסט רגיל'),
                    array('value'=>'template', 'title'=>'תבנית')
                )
            ),
            'template_language'=>array(
                'label'=>'שפה (לתבנית)',
                'type'=>'select',
                'default'=>'he',
                'options'=>array(
                    array('value'=>'he', 'title'=>'עברית'),
                    array('value'=>'en_US', 'title'=>'אנגלית')
                )
            ),
            'message_text'=>array(
                'label'=>'טקסט \ תבנית',
                'type'=>'text',
            ),

        );
    }

    

?>