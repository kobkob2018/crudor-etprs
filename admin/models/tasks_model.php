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
                array('value'=>'0', 'title'=>'לא'),
                array('value'=>'1', 'title'=>'כן')
            ),
            'validation'=>'required'
        ),
        'phone'=>array(
            'label'=>'טלפון',
            'type'=>'text',
            'validation'=>'phone'
        ),
        'email'=>array(
            'label'=>'אימייל',
            'validation'=>'required, email',
            'custom_validation'=>'task_email_validate_by',
            'readonly'=> true
        ),

        'banner'=>array(
            'label'=>'באנר',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'required, img',
            'img_max'=>'1000000',
            'upload_to'=>'tasks/banners',
            'name_file'=>'banner_{{row_id}}.{{ext}}'
        ),

        'form_img'=>array(
            'label'=>'תמונת טופס',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'1000000',
            'upload_to'=>'tasks/banners',
            'name_file'=>'form_img_{{row_id}}.{{ext}}'
        ),

        'visible'=>array(
            'label'=>'נראה באתר',
            'type'=>'select',
            'default'=>'1',
            'options_method'=>array('model'=>'Tasks','method'=>'get_select_yes_no_options'),
            'validation'=>'required'
        ),       

    );
}
?>