<?php
  class Language_messages extends TableModel{

    protected static $main_table = 'language_messages';

    public static $fields_collection = array(
        'msgid'=>array(
            'label'=>'משפט מקור',
            'type'=>'text',
            'validation'=>'required'
        ),
        'msgstr'=>array(
            'label'=>'תרגום',
            'type'=>'text',
            'validation'=>'required'
        ),
    );
}
?>