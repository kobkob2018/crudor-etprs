<?php
  class Cat_whatsapp_terms extends TableModel{

    protected static $main_table = 'cat_whatsapp_terms';

    public static $fields_collection = array(
        'term'=>array(
            'label'=>'מושג',
            'type'=>'text',
            'validation'=>'required'
        ),
    );
}
?>