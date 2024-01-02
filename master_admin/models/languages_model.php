<?php
  class Languages extends TableModel{

    protected static $main_table = 'languages';

    protected static $auto_delete_from_attached_tables = array(
        'language_messages'=>array(
            'table'=>'language_messages',
            'id_key'=>'language_id'
        ),
    );

    public static $fields_collection = array(
        'label'=>array(
            'label'=>'שפה',
            'type'=>'text',
            'validation'=>'required'
        ),
        'iso_code'=>array(
            'label'=>'קוד שפה',
            'type'=>'text',
            'validation'=>'required'
        ),
    );
}
?>