<?php
  class MasterUser_lead_api extends TableModel{

    protected static $main_table = 'user_lead_api';

    public static $fields_collection = array(

        'url'=>array(
            'label'=>'כתובת',
            'type'=>'text',
            'validation'=>'required'
        )

    );
}
?>