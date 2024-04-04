<?php 
    class Whatsapp_messages extends TableModel{
        protected static $main_table = 'whatsapp_messages';  

        public static $fields_collection = array(
            'image_link'=>array(
                'label'=>'הוספת תמונה',
                'type'=>'text',
            ),
            'video_link'=>array(
                'label'=>'הוספת וידאו',
                'type'=>'text',
            ),
            'message_text'=>array(
                'label'=>'תוכן ההודעה',
                'type'=>'text',
            ),

        );
    }

    

?>