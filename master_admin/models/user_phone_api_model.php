<?php
  class User_phone_api extends TableModel{

    protected static $main_table = 'user_phone_api';

    public static $fields_collection = array(

        'url'=>array(
            'label'=>'כתובת',
            'type'=>'text',
            'validation'=>'required'
        )

    );
}
?>