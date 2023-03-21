<?php
  class Refund_reasons extends TableModel{

    protected static $main_table = 'refund_reasons';

    public static $fields_collection = array(
        'label'=>array(
            'label'=>'תיאור הסיבה',
            'type'=>'text',
            'validation'=>'required'
        ),
    );
}
?>