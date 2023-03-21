<?php
  class AdminBlocks extends TableModel{

    protected static $main_table = 'content_blocks';


    public static $fields_collection = array(

        'label'=>array(
            'label'=>'שם לצורך זיהוי',
            'type'=>'text',
            'validation'=>''
        ),

        'css_class'=>array(
            'label'=>'תגית עיצוב',
            'type'=>'text',
            'default'=>'c-block'
        ),        

        'content'=>array(
            'label'=>'תוכן',
            'type'=>'textbox',
            'reachtext'=>true,
            'css_class'=>'big-text'
        ),    

    );
}
?>