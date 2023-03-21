<?php
  class Leads_filter extends TableModel{

    public static $fields_collection = array(

        'label'=>array(
            'label'=>'שם לצורך זיהוי',
            'type'=>'text',
            'validation'=>'validate',
            'validate_frontend'=>' validate ',
            'front_attributes'=>" required "
        ),

        'css_class'=>array(
            'label'=>'תגית עיצוב',
            'type'=>'text',
            'default'=>'c-block'
        ),        

        'note'=>array(
            'label'=>'תוכן',
            'type'=>'textbox',
            'reachtext'=>true,
            'css_class'=>'big-text'
        ),    

    );
}
?>