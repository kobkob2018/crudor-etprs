<?php
  class Net_banners extends TableModel{

    protected static $main_table = 'net_banners';

    public static $fields_collection = array(

        'label'=>array(
            'label'=>'כותרת',
            'type'=>'text',
            'validation'=>'required'
        ),

        'active'=>array(
            'label'=>'סטטוס',
            'type'=>'select',
            'default'=>'1',
            'options'=>array(
                array('value'=>'0', 'title'=>'לא פעיל'),
                array('value'=>'1', 'title'=>'פעיל')
            )
        ),

        'goto_href'=>array(
            'label'=>'לינק',
            'type'=>'text',
            'css_class'=>'left-text',
            'validation'=>''
        ),

        'free_html'=>array(
            'label'=>'HTML חופשי',
            'type'=>'textbox',
            'css_class'=>'small-text left-text',
            'validation'=>''
        ),

        'image'=>array(
            'label'=>'תמונה',
            'type'=>'file',
            'file_type'=>'img',
            'validation'=>'img',
            'img_max'=>'1000000',
            'upload_to'=>'net/banners',
            'name_file'=>'banner_{{row_id}}.{{ext}}'
        ),

        'video'=>array(
            'label'=>'וידאו',
            'type'=>'file',
            'file_type'=>'video',
            'validation'=>'video',
            'vid_max'=>'2147483648',
            'upload_to'=>'net/banners',
            'name_file'=>'vid_{{row_id}}.{{ext}}'
        ),

        'views'=>array(
            'label'=>'מספר צפיות',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'required, int'
        ),

        'clicks'=>array(
            'label'=>'מספר הקלקות',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'required, int'
        ),

        'convertions'=>array(
            'label'=>'מספר המרות',
            'type'=>'text',
            'default'=>'0',
            'validation'=>'required, int'
        ),
    );
}
?>