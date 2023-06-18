<?php
  class Tasks extends TableModel{

    protected static $main_table = 'tasks';


    public static $fields_collection = array(
        'user_id'=>array(
            'label'=>'שיוך למשתמש',
            'type'=>'select',
            'options_method'=>array('model'=>'Tasks','method'=>'get_select_user_options'),
            'validation'=>'required'
        ),
        'title'=>array(
            'label'=>'כותרת',
            'type'=>'text',
            'validation'=>'required'
        ),
        'description'=>array(
            'label'=>'תיאור',
            'type'=>'textbox',
        ),
        'status'=>array(
            'label'=>'סטטוס',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'1', 'title'=>'משימה חדשה'),
                array('value'=>'2', 'title'=>'בתהליך'),
                array('value'=>'3', 'title'=>'בוצע'),
                array('value'=>'4', 'title'=>'בדיון'),
                array('value'=>'0', 'title'=>'בוטל'),
            ),
            'validation'=>'required'
        )
    );
}
?>